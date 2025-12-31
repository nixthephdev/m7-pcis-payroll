<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Import the PDF Library

class PayrollController extends Controller
{
    // Function 1: Generate the Salary
    public function generatePayroll() {
        $employee = Auth::user()->employee;

        // Count days worked
        $daysWorked = Attendance::where('employee_id', $employee->id)
                        ->whereMonth('date', Carbon::now()->month)
                        ->count();

        // Calculate Salary
        $dailyRate = $employee->basic_salary / 22;
        $grossSalary = $dailyRate * $daysWorked;

        // Calculate Deductions (10%)
        $deductions = $grossSalary * 0.10;
        $netSalary = $grossSalary - $deductions;

        // Save to Database
        Payroll::create([
            'employee_id' => $employee->id,
            'pay_date' => Carbon::now(),
            'gross_salary' => number_format($grossSalary, 2, '.', ''),
            'deductions' => number_format($deductions, 2, '.', ''),
            'net_salary' => number_format($netSalary, 2, '.', '')
        ]);

        return redirect()->back()->with('message', 'Payroll Generated! Net Pay: ' . number_format($netSalary, 2));
    }

    // Function 2: Download the PDF (This was missing!)
    public function downloadPdf($id) {
        // Find the payroll record
        $payroll = Payroll::with('employee.user')->findOrFail($id);

        // Security Check: Ensure the user owns this payslip
        if(Auth::user()->employee->id !== $payroll->employee_id) {
            abort(403, 'Unauthorized action.');
        }

        // Load the PDF view
        $pdf = Pdf::loadView('payroll.pdf', compact('payroll'));

        // Download the file
        return $pdf->download('Payslip-M7-'.$payroll->id.'.pdf');
    }
}