<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Employee;

class AttendanceController extends Controller
{
    // --- API: KIOSK SCANNER LOGIC (Uses Employee Code) ---
    public function scan(Request $request) {
        $scannedCode = $request->input('employee_id'); // This gets the QR text (e.g. "2025-001")
        
        // 1. Find Employee by their UNIQUE CODE (Not ID)
        $employee = Employee::with('user')->where('employee_code', $scannedCode)->first();

        if (!$employee) {
            return response()->json(['status' => 'error', 'message' => 'Invalid QR Code. User not found.']);
        }

        $today = Carbon::today();
        $now = Carbon::now();

        // 2. Check existing attendance for today
        $attendance = Attendance::where('employee_id', $employee->id)
                                ->where('date', $today)
                                ->first();

        if (!$attendance) {
            // --- CLOCK IN ---
            // Logic: Mark Late if after 9:00 AM
            $status = $now->format('H:i') > '09:00' ? 'Late' : 'Present';

            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'time_in' => $now,
                'status' => $status
            ]);
            
            return response()->json([
                'status' => 'success', 
                'message' => "Welcome, " . $employee->user->name . "! Clocked In."
            ]);
        } 
        elseif ($attendance->time_out == null) {
            // --- CLOCK OUT ---
            // Prevent double scanning (must wait 1 minute between scans)
            if ($now->diffInMinutes($attendance->time_in) < 1) {
                return response()->json(['status' => 'error', 'message' => 'Already scanned. Please wait a moment.']);
            }

            $attendance->update(['time_out' => $now]);
            
            return response()->json([
                'status' => 'success', 
                'message' => "Goodbye, " . $employee->user->name . "! Clocked Out."
            ]);
        } 
        else {
            // --- ALREADY DONE ---
            return response()->json(['status' => 'error', 'message' => 'You have already completed your shift today.']);
        }
    }

    // --- DASHBOARD: MANUAL BUTTONS (Uses Auth User) ---
    public function clockIn() {
        $employee = Auth::user()->employee;

        if (!$employee) {
            return redirect()->back()->with('message', 'Error: No Employee Record Found.');
        }

        $existingAttendance = Attendance::where('employee_id', $employee->id)->where('date', Carbon::today())->first();

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
        $attendance = Attendance::where('employee_id', $employee->id)->where('date', Carbon::today())->first();

        if ($attendance) {
            $attendance->update(['time_out' => Carbon::now()]);
            return redirect()->back()->with('message', 'Clocked Out Successfully!');
        }

        return redirect()->back()->with('message', 'You have not clocked in yet!');
    }

    // --- ADMIN: ATTENDANCE MANAGEMENT ---
    public function index() {
        $attendances = Attendance::with('employee.user')
                                 ->orderBy('date', 'desc')
                                 ->orderBy('time_in', 'desc')
                                 ->get();
                                 
        return view('attendance.index', compact('attendances'));
    }

    public function edit($id) {
        $attendance = Attendance::with('employee.user')->findOrFail($id);
        return view('attendance.edit', compact('attendance'));
    }

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