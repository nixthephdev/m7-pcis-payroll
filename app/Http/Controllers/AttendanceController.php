<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    // --- API: KIOSK SCANNER LOGIC ---
    public function scan(Request $request) {
        try {
            $scannedCode = $request->input('employee_id');
            
            $person = null;
            $type = '';

            // 1. Search in Employees
            $employee = Employee::with('user')->where('employee_code', $scannedCode)->first();
            if ($employee) {
                $person = $employee;
                $type = 'App\Models\Employee';
            } else {
                // 2. Search in Students
                $student = Student::with('user')->where('student_id', $scannedCode)->first();
                if ($student) {
                    $person = $student;
                    $type = 'App\Models\Student';
                }
            }

            if (!$person) {
                return response()->json(['status' => 'error', 'message' => 'ID not found: ' . $scannedCode]);
            }

            $today = Carbon::today();
            $now = Carbon::now();

            // 3. Find Latest Record
            $latestAttendance = Attendance::where('attendable_id', $person->id)
                                          ->where('attendable_type', $type)
                                          ->orderBy('created_at', 'desc')
                                          ->first();

            // 4. Logic: Clock In or Out
            if (!$latestAttendance || $latestAttendance->time_out != null) {
                
                // --- UNIVERSAL SCHEDULE LOGIC ---
                $status = 'Present'; // Default
                
                // 1. STUDENT LOGIC
                if ($type == 'App\Models\Student') {
                    if ($now->format('H:i:s') > '07:30:00') {
                        $status = 'Late';
                    }
                }
                
                // 2. EMPLOYEE LOGIC (Using Schedule Relationship)
                elseif ($type == 'App\Models\Employee') {
                    
                    $person->load('schedule'); 
                    $schedule = $person->schedule;

                    if ($schedule) {
                        // CASE A: Flexible Schedule -> Always Present
                        if ($schedule->is_flexible || $schedule->name === 'Flexible') {
                            $status = 'Present';
                        }
                        // CASE B: Fixed Schedule -> Check Time
                        else {
                            $graceTime = Carbon::parse($schedule->time_in)->addMinutes(15)->format('H:i:s');
                            if ($now->format('H:i:s') > $graceTime) {
                                $status = 'Late';
                            }
                        }
                    } else {
                        // Fallback if no schedule assigned
                        if ($now->format('H:i:s') > '08:15:00') {
                            $status = 'Late';
                        }
                    }
                }

                Attendance::create([
                    'attendable_id' => $person->id,
                    'attendable_type' => $type,
                    'date' => $today,
                    'time_in' => $now->format('H:i:s'),
                    'status' => $status
                ]);
                
                return response()->json([
                    'status' => 'success', 
                    'message' => "Welcome, " . $person->user->name . "! Clocked In."
                ]);
            } 
            else {
                // --- CLOCK OUT ---
                $lastTime = Carbon::parse($latestAttendance->date . ' ' . $latestAttendance->time_in);
                if ($now->diffInMinutes($lastTime) < 1) {
                    return response()->json(['status' => 'error', 'message' => 'Already scanned. Please wait.']);
                }

                $latestAttendance->update(['time_out' => $now->format('H:i:s')]);
                
                return response()->json([
                    'status' => 'success', 
                    'message' => "Goodbye, " . $person->user->name . "! Clocked Out."
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'System Error: ' . $e->getMessage()
            ]);
        }
    }

    // --- DASHBOARD: MANUAL BUTTONS ---
    public function clockIn() {
        $employee = Auth::user()->employee;
        if (!$employee) return redirect()->back()->with('message', 'Error: No Employee Record Found.');

        $existing = Attendance::where('attendable_id', $employee->id)
                              ->where('attendable_type', 'App\Models\Employee')
                              ->where('date', Carbon::today())
                              ->first();

        if ($existing) return redirect()->back()->with('message', 'You have already clocked in today!');

        // Manual Clock In Logic
        $schedule = $employee->schedule;
        $status = 'Present';
        $now = Carbon::now();

        if ($schedule && !$schedule->is_flexible) {
            $graceTime = Carbon::parse($schedule->time_in)->addMinutes(15)->format('H:i:s');
            if ($now->format('H:i:s') > $graceTime) {
                $status = 'Late';
            }
        } elseif (!$schedule) {
             if ($now->format('H:i:s') > '08:15:00') {
                $status = 'Late';
            }
        }

        Attendance::create([
            'attendable_id' => $employee->id,
            'attendable_type' => 'App\Models\Employee',
            'date' => Carbon::today(),
            'time_in' => $now->format('H:i:s'),
            'status' => $status
        ]);

        return redirect()->back()->with('message', 'Clocked In Successfully!');
    }

    public function clockOut() {
        $employee = Auth::user()->employee;
        $attendance = Attendance::where('attendable_id', $employee->id)
                                ->where('attendable_type', 'App\Models\Employee')
                                ->where('date', Carbon::today())
                                ->first();

        if ($attendance) {
            $attendance->update(['time_out' => Carbon::now()->format('H:i:s')]);
            return redirect()->back()->with('message', 'Clocked Out Successfully!');
        }

        return redirect()->back()->with('message', 'You have not clocked in yet!');
    }

    // --- ADMIN: ATTENDANCE MANAGEMENT ---
    public function index() {
        $attendances = Attendance::with('attendable.user')
                                 ->orderBy('date', 'desc')
                                 ->orderBy('time_in', 'desc')
                                 ->get();
        return view('attendance.index', compact('attendances'));
    }

    public function edit($id) {
        $attendance = Attendance::with('attendable.user')->findOrFail($id);
        return view('attendance.edit', compact('attendance'));
    }

    public function update(Request $request, $id) {
        $attendance = Attendance::findOrFail($id);
        
        $attendance->time_in = $request->time_in . ':00';
        $attendance->time_out = $request->time_out ? $request->time_out . ':00' : null;
        $attendance->status = $request->status;
        $attendance->save();

        \App\Models\AuditLog::record('Edited Attendance', 'Manually modified attendance for ID: ' . $id);

        return redirect()->route('attendance.index')->with('message', 'Attendance record updated successfully.');
    }
}
