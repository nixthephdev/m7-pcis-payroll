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

        // 1. If ADMIN, gather Executive Stats & Show Admin Dashboard
        if ($user->role === 'admin') {
            
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

            // Table: Recent Attendance
            $recentAttendance = Attendance::with('attendable.user')
                                          ->where('date', Carbon::today())
                                          ->orderBy('time_in', 'desc')
                                          ->take(5)
                                          ->get();

            // Admin's Personal Data (For "My Payslips" section)
            $adminEmployee = $user->employee;
            $myPayrolls = collect();

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
                'myPayrolls', 
                'adminEmployee'
            ));
        }

        // 2. ALL OTHER ROLES (Employee AND Guard)
        // They go to the standard dashboard
        return view('dashboard');
    }
}