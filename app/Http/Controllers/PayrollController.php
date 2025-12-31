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
    // Function 1: Generate the Salary (Updated with Dynamic Items)
    public function generatePayroll() {
        // 1. Get the current logged-in employee with their Salary Items
        // We use 'with' to load the allowances/deductions efficiently
        $employee = Auth::user()->employee()->with('salaryItems')->first();

        if (!$employee) {
            return redirect()->back()->with('message', 'Error: No Employee Record Found.');
        }

        // 2. Count days worked this month
        $daysWorked = Attendance::where('employee_id', $employee->id)
                        ->whereMonth('date', Carbon::now()->month)
                        ->count();

        // 3. Calculate Basic Pay based on Attendance
        // (Assuming 22 working days = 1 Month Salary)
        $dailyRate = $employee->basic_salary / 22;
        $calculatedBasicPay = $dailyRate * $daysWorked;

        // 4. Calculate Extra Earnings (Allowances)
        // We sum up all items marked as 'earning'
        $totalAllowances = $employee->salaryItems->where('type', 'earning')->sum('amount');

        // 5. Calculate Total Deductions
        // We sum up all items marked as 'deduction'
        $totalDeductions = $employee->salaryItems->where('type', 'deduction')->sum('amount');

        // 6. Final Math
        $grossSalary = $calculatedBasicPay + $totalAllowances;
        $netSalary = $grossSalary - $totalDeductions;

        // 7. Save to Database
        Payroll::create([
            'employee_id' => $employee->id,
            'pay_date' => Carbon::now(),
            'gross_salary' => number_format($grossSalary, 2, '.', ''),
            'deductions' => number_format($totalDeductions, 2, '.', ''),
            'net_salary' => number_format($netSalary, 2, '.', ''),
            'status' => 'Pending' // <--- ADD THIS LINE TO BOTH FUNCTIONS
        ]);

        return redirect()->back()->with('message', 'Payroll Generated! Net Pay: ₱' . number_format($netSalary, 2));
    }

    public function downloadPdf($id) {
        // Load the Payroll + Employee + User + Salary Items
        $payroll = Payroll::with(['employee.user', 'employee.salaryItems'])->findOrFail($id);

        // Security Check
        if(Auth::user()->employee->id !== $payroll->employee_id) {
            abort(403, 'Unauthorized action.');
        }

        $pdf = Pdf::loadView('payroll.pdf', compact('payroll'));
        return $pdf->download('Payslip-M7-'.$payroll->id.'.pdf');
    }

    // ADMIN FUNCTION: Generate Payroll for a specific employee
    public function generateForEmployee($id) {
        // 1. Get the specific employee
        $employee = Employee::with('salaryItems')->findOrFail($id);

        // 2. Count days worked this month
        $daysWorked = Attendance::where('employee_id', $employee->id)
                        ->whereMonth('date', Carbon::now()->month)
                        ->count();

        // 3. Calculate Basic Pay
        $dailyRate = $employee->basic_salary / 22;
        $calculatedBasicPay = $dailyRate * $daysWorked;

        // 4. Calculate Earnings & Deductions
        $totalAllowances = $employee->salaryItems->where('type', 'earning')->sum('amount');
        $totalDeductions = $employee->salaryItems->where('type', 'deduction')->sum('amount');

        // 5. Final Math
        $grossSalary = $calculatedBasicPay + $totalAllowances;
        $netSalary = $grossSalary - $totalDeductions;

        // 6. Save to Database
        Payroll::create([
            'employee_id' => $employee->id,
            'pay_date' => Carbon::now(),
            'gross_salary' => number_format($grossSalary, 2, '.', ''),
            'deductions' => number_format($totalDeductions, 2, '.', ''),
            'net_salary' => number_format($netSalary, 2, '.', '')
        ]);

        return redirect()->back()->with('message', 'Payroll Generated for ' . $employee->user->name);
    }

    // ADMIN: Mark a payroll as Paid
    public function markAsPaid($id) {
        $payroll = Payroll::findOrFail($id);
        
        $payroll->update([
            'status' => 'Paid'
        ]);

        return redirect()->back()->with('message', 'Payroll marked as PAID.');
    }

    // ADMIN: View All Payroll History
    public function history() {
        // Get all payrolls, sorted by newest first
        $payrolls = Payroll::with('employee.user')
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('payroll.index', compact('payrolls'));
    }
}