<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    // Show the Leave Page
    public function index() {
        $employee = Auth::user()->employee;
        
        // Get all leaves for this employee, ordered by newest first
        $leaves = LeaveRequest::where('employee_id', $employee->id)
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('leaves.index', compact('leaves'));
    }

    // Store a new Leave Request
    public function store(Request $request) {
        $request->validate([
            'leave_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required'
        ]);

        $employee = Auth::user()->employee;
        
        // Calculate Days
        $start = \Carbon\Carbon::parse($request->start_date);
        $end = \Carbon\Carbon::parse($request->end_date);
        $daysRequested = $start->diffInDays($end) + 1;

        // Check Credits
        if ($request->leave_type == 'Vacation Leave' && $employee->vacation_credits < $daysRequested) {
            return redirect()->back()->with('error', 'Not enough Vacation Leave credits!');
        }
        if ($request->leave_type == 'Sick Leave' && $employee->sick_credits < $daysRequested) {
            return redirect()->back()->with('error', 'Not enough Sick Leave credits!');
        }

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'Pending'
        ]);

        return redirect()->back()->with('message', 'Leave request submitted successfully.');
    }

    // 1. Show the Admin View (List of all requests)
    public function manage() {
        // Get all leaves with employee details, newest first
        $leaves = LeaveRequest::with('employee.user')
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('leaves.manage', compact('leaves'));
    }

    // 2. Process the Approval/Rejection
    public function updateStatus(Request $request, $id) {
        $leave = LeaveRequest::findOrFail($id);
        $employee = $leave->employee;

        // If Approving, Deduct Credits
        if ($request->status == 'Approved' && $leave->status != 'Approved') {
            
            $days = \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1;

            if ($leave->leave_type == 'Vacation Leave') {
                if ($employee->vacation_credits >= $days) {
                    $employee->decrement('vacation_credits', $days);
                } else {
                    return redirect()->back()->with('error', 'Cannot approve: Insufficient credits.');
                }
            }
            elseif ($leave->leave_type == 'Sick Leave') {
                if ($employee->sick_credits >= $days) {
                    $employee->decrement('sick_credits', $days);
                } else {
                    return redirect()->back()->with('error', 'Cannot approve: Insufficient credits.');
                }
            }
        }

        $leave->update(['status' => $request->status]);

        return redirect()->back()->with('message', 'Leave request updated successfully.');
    }
}