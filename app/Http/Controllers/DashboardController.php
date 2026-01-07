<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Student;
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

        // --- 1. ADMIN VIEW (Executive Overview) ---
        if ($user->role === 'admin') {
            
            // Calculate Population
            $totalEmployees = Employee::count();
            $totalStudents = Student::count();
            $totalPopulation = $totalEmployees + $totalStudents;

            // Calculate Attendance (Everyone present today)
            $presentToday = Attendance::where('date', Carbon::today())
                            ->whereIn('status', ['Present', 'Late'])
                            ->count();

            // Pending Leave Requests
            $pendingLeaves = LeaveRequest::where('status', 'Pending')->count();

            // Total Payroll Cost (This Month)
            $monthlyCost = Payroll::whereMonth('pay_date', Carbon::now()->month)
                            ->sum('net_salary');

            // Recent Attendance Feed
            $recentAttendance = Attendance::with('attendable')
                                ->where('date', Carbon::today())
                                ->orderBy('time_in', 'desc')
                                ->take(5)
                                ->get();

            // Admin's Personal Data
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
                'totalStudents',
                'totalPopulation',
                'presentToday', 
                'pendingLeaves', 
                'monthlyCost', 
                'recentAttendance', 
                'myPayrolls', 
                'adminEmployee'
            ));
        }

        // --- 2. GUARD & EMPLOYEE VIEW (Standard Dashboard) ---
        return view('dashboard');
    }
}