<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    public function generateForEmployee(Request $request, $id) {
        $employee = Employee::with('salaryItems')->findOrFail($id);
        $period = $request->input('period');
        $monthlyBasic = $employee->basic_salary; 
        $grossSalary = 0;
        $totalDeductions = 0;
        $netSalary = 0;

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

        return redirect()->back()->with('message', "$period Payroll Generated.");
    }

    // --- ADMIN: Generate Payroll for ALL Employees (Bulk) ---
    public function generateAll(Request $request) {
        $employees = Employee::with('salaryItems')->get();
        $period = $request->input('period'); // Get 'Mid-Month' or 'End-Month' from the button
        $count = 0;

        foreach($employees as $employee) {
            // 1. Calculate Basic
            $monthlyBasic = $employee->basic_salary;
            $grossSalary = 0;
            $totalDeductions = 0;
            $netSalary = 0;

            // 2. Apply Logic based on Period
            if ($period == 'Mid-Month') {
                $grossSalary = $monthlyBasic / 2;
                $netSalary = $grossSalary;
            } 
            elseif ($period == 'End-Month') {
                $basicHalf = $monthlyBasic / 2;
                $totalAllowances = $employee->salaryItems->where('type', 'earning')->sum('amount');
                $totalDeductions = $employee->salaryItems->where('type', 'deduction')->sum('amount');

                $grossSalary = $basicHalf + $totalAllowances;
                $netSalary = $grossSalary - $totalDeductions;
            }

            // 3. Save
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
        return redirect()->back()->with('message', 'Payroll marked as PAID.');
    }

    public function downloadPdf($id) {
        $payroll = Payroll::with(['employee.user', 'employee.salaryItems'])->findOrFail($id);

        if(Auth::user()->role !== 'admin' && Auth::user()->employee && Auth::user()->employee->id !== $payroll->employee_id) {
            abort(403, 'Unauthorized action.');
        }

        // Prepare Logo
        $path = public_path('images/logo.png');
        $logoBase64 = '';
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // --- NEW FILENAME LOGIC ---
        $user = $payroll->employee->user;
        $date = Carbon::now()->format('m-d-Y'); // 03-11-2026 (Slashes / are not allowed in filenames)
        
        // Split Name (Assuming "First Last" format in DB)
        $nameParts = explode(' ', $user->name);
        $lastName = array_pop($nameParts); // Get last word
        $firstName = implode(' ', $nameParts); // Get rest
        $formattedName = $lastName . ', ' . $firstName;

        // Clean Filename
        $filename = "{$formattedName} - {$payroll->period} - Payslip - {$date}.pdf";
        // --------------------------

        $pdf = Pdf::loadView('payroll.pdf', compact('payroll', 'logoBase64'));
        return $pdf->download($filename);
    }
    
    public function generatePayroll() {
        return redirect()->route('dashboard');
    }
    // ADMIN: Delete a Payroll Record (Undo)
    public function destroy($id) {
        $payroll = Payroll::findOrFail($id);
        
        $payroll->delete();

        return redirect()->back()->with('message', 'Payroll record deleted successfully.');
    }
}
