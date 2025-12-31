<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    // --- EMPLOYEE FUNCTIONS ---

    public function clockIn() {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect()->back()->with('message', 'Error: No Employee Record Found.');
        }

        $existingAttendance = Attendance::where('employee_id', $employee->id)
                                        ->where('date', Carbon::today())
                                        ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('message', 'You have already clocked in today!');
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => Carbon::today(),
            'time_in' => Carbon::now(),
            'status' => 'Present'
        ]);

        return redirect()->back()->with('message', 'Clocked In Successfully!');
    }

    public function clockOut() {
        $employee = Auth::user()->employee;

        $attendance = Attendance::where('employee_id', $employee->id)
                                ->where('date', Carbon::today())
                                ->first();

        if ($attendance) {
            $attendance->update(['time_out' => Carbon::now()]);
            return redirect()->back()->with('message', 'Clocked Out Successfully!');
        }

        return redirect()->back()->with('message', 'You have not clocked in yet!');
    }

    // --- ADMIN FUNCTIONS (NEW) ---

    // 1. Show All Attendance Records
    public function index() {
        $attendances = Attendance::with('employee.user')
                                 ->orderBy('date', 'desc')
                                 ->orderBy('time_in', 'desc')
                                 ->get();
                                 
        return view('attendance.index', compact('attendances'));
    }

    // 2. Show the Edit Form
    public function edit($id) {
        $attendance = Attendance::with('employee.user')->findOrFail($id);
        return view('attendance.edit', compact('attendance'));
    }

    // 3. Save Changes
    public function update(Request $request, $id) {
        $request->validate([
            'time_in' => 'required',
            'time_out' => 'nullable',
            'status' => 'required|string'
        ]);

        $attendance = Attendance::findOrFail($id);
        
        $attendance->update([
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'status' => $request->status
        ]);

        return redirect()->route('attendance.index')->with('message', 'Attendance record updated successfully.');
    }
}