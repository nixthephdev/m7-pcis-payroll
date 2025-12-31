<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    // Function to handle Clock In
    public function clockIn() {
        $employee = Auth::user()->employee;

        // Check if employee exists
        if (!$employee) {
            return redirect()->back()->with('message', 'Error: No Employee Record Found for this User.');
        }

        // Check if already clocked in today
        $existingAttendance = Attendance::where('employee_id', $employee->id)
                                        ->where('date', Carbon::today())
                                        ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('message', 'You have already clocked in today!');
        }

        // Create Attendance Record
        Attendance::create([
            'employee_id' => $employee->id,
            'date' => Carbon::today(),
            'time_in' => Carbon::now(),
            'status' => 'Present'
        ]);

        return redirect()->back()->with('message', 'Clocked In Successfully!');
    }

    // Function to handle Clock Out
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
}