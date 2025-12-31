<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. If user is an EMPLOYEE, show the standard dashboard
        if ($user->role !== 'admin') {
            return view('dashboard');
        }

        // 2. If user is ADMIN, gather Executive Stats
        
        // Card 1: Total Employees
        $totalEmployees = Employee::count();

        // Card 2: Present Today
        $presentToday = Attendance::where('date', Carbon::today())
                                  ->where('status', '!=', 'Absent')
                                  ->count();

        // Card 3: Pending Leave Requests
        $pendingLeaves = LeaveRequest::where('status', 'Pending')->count();

        // Card 4: Total Payroll Cost (This Month)
        $monthlyCost = Payroll::whereMonth('pay_date', Carbon::now()->month)
                              ->sum('net_salary');

        // Table: Recent Attendance (Who clocked in recently?)
        $recentAttendance = Attendance::with('employee.user')
                                      ->where('date', Carbon::today())
                                      ->orderBy('time_in', 'desc')
                                      ->take(5)
                                      ->get();

        // --- NEW: Admin's Personal Data ---
        $adminEmployee = $user->employee; // Get Admin's employee record
        $myPayrolls = collect(); // Empty collection by default

        if ($adminEmployee) {
            $myPayrolls = Payroll::where('employee_id', $adminEmployee->id)
                                 ->orderBy('created_at', 'desc')
                                 ->take(5)
                                 ->get();
        }

        return view('admin_dashboard', compact(
            'totalEmployees', 
            'presentToday', 
            'pendingLeaves', 
            'monthlyCost',
            'recentAttendance',
            'myPayrolls', // <--- This was missing before
            'adminEmployee' // <--- This was missing before
        ));
    }
}