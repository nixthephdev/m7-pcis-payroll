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
            'reason' => 'required|string|max:255',
        ]);

        LeaveRequest::create([
            'employee_id' => Auth::user()->employee->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'Pending'
        ]);

        return redirect()->back()->with('message', 'Leave Request Filed Successfully!');
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
        
        $leave->update([
            'status' => $request->status // This will be 'Approved' or 'Rejected'
        ]);

        return redirect()->back()->with('message', 'Leave request updated successfully.');
    }
}