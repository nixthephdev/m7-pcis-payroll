<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AuditLog; 

class PayrollController extends Controller
{
    public function generateForEmployee(Request $request, $id) {
        $employee = Employee::with('salaryItems')->findOrFail($id);
        $period = $request->input('period');
        $monthlyBasic = $employee->basic_salary; 
        $grossSalary = 0;
        $totalDeductions = 0;
        $netSalary = 0;

        // Count Days Worked (Polymorphic Fix)
        $daysWorked = Attendance::where('attendable_id', $employee->id)
                                ->where('attendable_type', 'App\Models\Employee')
                                ->whereMonth('date', Carbon::now()->month)
                                ->count();

        if ($period == 'Mid-Month') {
            $grossSalary = $monthlyBasic / 2;
            $netSalary = $grossSalary;
        }

        if ($period == 'End-Month') {
            $basicHalf = $monthlyBasic / 2;
            $totalAllowances = $employee->salaryItems->where('type', 'earning')->sum('amount');
            $totalDeductions = $employee->salaryItems->where('type', 'deduction')->sum('amount');
            $grossSalary = $basicHalf + $totalAllowances;
            $netSalary = $grossSalary - $totalDeductions;
        }

        Payroll::create([
            'employee_id' => $employee->id,
            'pay_date' => Carbon::now(),
            'period' => $period,
            'gross_salary' => number_format($grossSalary, 2, '.', ''),
            'deductions' => number_format($totalDeductions, 2, '.', ''),
            'net_salary' => number_format($netSalary, 2, '.', ''),
            'status' => 'Pending'
        ]);

        \App\Models\AuditLog::record('Generated Payroll', 'Generated ' . $period . ' payroll for ' . $employee->user->name);

        return redirect()->back()->with('message', "$period Payroll Generated.");
    }

    public function generateAll(Request $request) {
        $employees = Employee::with('salaryItems')->get();
        $period = $request->input('period');
        $count = 0;

        foreach($employees as $employee) {
            $monthlyBasic = $employee->basic_salary;
            $grossSalary = 0;
            $totalDeductions = 0;
            $netSalary = 0;

            if ($period == 'Mid-Month') {
                $grossSalary = $monthlyBasic / 2;
                $netSalary = $grossSalary;
            } elseif ($period == 'End-Month') {
                $basicHalf = $monthlyBasic / 2;
                $totalAllowances = $employee->salaryItems->where('type', 'earning')->sum('amount');
                $totalDeductions = $employee->salaryItems->where('type', 'deduction')->sum('amount');
                $grossSalary = $basicHalf + $totalAllowances;
                $netSalary = $grossSalary - $totalDeductions;
            }

            Payroll::create([
                'employee_id' => $employee->id,
                'pay_date' => Carbon::now(),
                'period' => $period,
                'gross_salary' => number_format($grossSalary, 2, '.', ''),
                'deductions' => number_format($totalDeductions, 2, '.', ''),
                'net_salary' => number_format($netSalary, 2, '.', ''),
                'status' => 'Pending'
            ]);
            $count++;
        }

        return redirect()->back()->with('message', "Success! Generated $period payroll for $count employees.");
    }

    public function history() {
        $payrolls = Payroll::with('employee.user')->orderBy('created_at', 'desc')->get();
        return view('payroll.index', compact('payrolls'));
    }

    public function markAllAsPaid() {
        $count = Payroll::where('status', 'Pending')->update(['status' => 'Paid']);
        return redirect()->back()->with('message', "$count records marked as PAID.");
    }

    public function markAsPaid($id) {
        $payroll = Payroll::findOrFail($id);
        $payroll->update(['status' => 'Paid']);

        \App\Models\AuditLog::record('Paid Salary', 'Marked payroll #' . $id . ' as Paid');

        return redirect()->back()->with('message', 'Payroll marked as PAID.');
    }

    public function destroy($id) {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();

        \App\Models\AuditLog::record('Deleted Payroll', 'Deleted a payroll record.');
        
        return redirect()->back()->with('message', 'Payroll record deleted successfully.');
    }

    public function downloadPdf($id) {
        // --- SECURITY UPDATE: Strict Check for Admin Role ---
        if(Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only HR/Admins can download payslips for now.');
        }
        // ----------------------------------------------------

        $payroll = Payroll::with(['employee.user', 'employee.salaryItems'])->findOrFail($id);

        $path = public_path('images/logo.png');
        $logoBase64 = '';
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $user = $payroll->employee->user;
        $date = Carbon::now()->format('m-d-Y');
        $nameParts = explode(' ', $user->name);
        $lastName = array_pop($nameParts);
        $firstName = implode(' ', $nameParts);
        $formattedName = $lastName . ', ' . $firstName;
        $filename = "{$formattedName} - {$payroll->period} - Payslip - {$date}.pdf";

        $pdf = Pdf::loadView('payroll.pdf', compact('payroll', 'logoBase64'));
        return $pdf->download($filename);
    }

    public function index()
    {
        $user = Auth::user();

        // 1. If ADMIN, gather Executive Stats & Show Admin Dashboard
        if ($user->role === 'admin') {
            // ... (Keep all your existing Admin Logic here) ...
            
            return view('admin_dashboard', compact(
                'totalEmployees', 
                'presentToday', 
                'pendingLeaves', 
                'monthlyCost', 
                'recentAttendance', 
                'myPayrolls', 
                'adminEmployee'
            ));
        }

        // 2. If GUARD or EMPLOYEE, show the Standard Dashboard
        return view('dashboard');
    }

    // --- 13TH MONTH PAY MODULE ---

    // 1. View the 13th Month Dashboard
    public function index13th() {
        $employees = Employee::with('user')->get();
        $currentYear = Carbon::now()->year;
        
        $data = [];

        foreach($employees as $emp) {
            // Calculate Total Days Worked this Year
            $daysWorked = Attendance::where('attendable_id', $emp->id)
                                    ->where('attendable_type', 'App\Models\Employee')
                                    ->whereYear('date', $currentYear)
                                    ->count();
            
            // Daily Rate
            $dailyRate = $emp->basic_salary / 22;

            // Total Basic Earned (Year to Date)
            $totalBasicEarned = $daysWorked * $dailyRate;

            // 13th Month Calculation
            $thirteenthMonth = $totalBasicEarned / 12;

            // Check if already paid this year
            $isPaid = Payroll::where('employee_id', $emp->id)
                             ->where('period', '13th-Month')
                             ->whereYear('pay_date', $currentYear)
                             ->exists();

            $data[] = [
                'employee' => $emp,
                'total_basic' => $totalBasicEarned,
                'thirteenth_pay' => $thirteenthMonth,
                'is_paid' => $isPaid
            ];
        }

        return view('payroll.13th_month', compact('data', 'currentYear'));
    }

    // 2. Generate the 13th Month Record
    public function generate13th($id) {
        $employee = Employee::findOrFail($id);
        $currentYear = Carbon::now()->year;

        // Recalculate to be safe
        $daysWorked = Attendance::where('attendable_id', $employee->id)
                                ->where('attendable_type', 'App\Models\Employee')
                                ->whereYear('date', $currentYear)
                                ->count();
        
        $dailyRate = $employee->basic_salary / 22;
        $totalBasicEarned = $daysWorked * $dailyRate;
        $amount = $totalBasicEarned / 12;

        // Save as a Payroll Record
        Payroll::create([
            'employee_id' => $employee->id,
            'pay_date' => Carbon::now(),
            'period' => '13th-Month', // Special Period Name
            'gross_salary' => number_format($amount, 2, '.', ''),
            'deductions' => 0, // 13th Month is usually non-taxable (up to 90k)
            'net_salary' => number_format($amount, 2, '.', ''),
            'status' => 'Pending'
        ]);

        return redirect()->back()->with('message', '13th Month Pay generated for ' . $employee->user->name);
    }
}