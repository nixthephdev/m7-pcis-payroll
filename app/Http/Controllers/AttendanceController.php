<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; 

class AttendanceController extends Controller
{
    // --- 1. ADMIN: ATTENDANCE MANAGEMENT (LIST) ---
    public function index(Request $request) {
        $type = $request->get('type', 'employee');

        $query = Attendance::with('attendable'); 

        if ($type === 'student') {
            $query->where('attendable_type', 'App\Models\Student');
        } else {
            $query->where('attendable_type', 'App\Models\Employee');
        }

        $attendances = $query->orderBy('date', 'desc')
                             ->orderBy('time_in', 'desc')
                             ->get();

        return view('attendance.index', compact('attendances', 'type'));
    }

    // --- 2. KIOSK VIEW ---
    public function scanPage() {
        return view('attendance.scan');
    }

    // --- 3. SCAN LOGIC (QR CODE) ---
    // --- 3. SCAN LOGIC (QR CODE) ---
    public function scan(Request $request)
    {
        $id = $request->input('employee_id');

        // 1. Find Person
        $person = Employee::where('employee_code', $id)->first();
        $type = 'App\Models\Employee';

        if (!$person) {
            $person = Student::where('student_id', $id)->first();
            $type = 'App\Models\Student';
        }

        if (!$person) {
            return response()->json(['status' => 'error', 'message' => 'ID Number not found.']);
        }

        // 2. Get Name
        $name = 'Unknown';
        if ($type === 'App\Models\Employee') {
            $name = $person->user ? $person->user->name : 'Unknown Employee';
        } else {
            $name = $person->full_name ?? ($person->first_name . ' ' . $person->last_name);
        }

        $now = Carbon::now();
        $date = $now->format('Y-m-d');

        // 3. Check for existing record
        $attendance = Attendance::where('attendable_id', $person->id)
                                ->where('attendable_type', $type)
                                ->where('date', $date)
                                ->first();

        if ($attendance) {
            // --- TIME OUT LOGIC ---

            // A. Check if already timed out
            if ($attendance->time_out) {
                return response()->json(['status' => 'error', 'message' => 'Already timed out today!']);
            }

            // B. CHECK INTERVAL (Prevent Double Punch)
            $timeIn = Carbon::parse($attendance->time_in);
            $minutesPassed = $timeIn->diffInMinutes($now);

            if ($minutesPassed < 5) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Scan ignored. Please wait 5 minutes before clocking out.'
                ]);
            }

            // C. Process Time Out
            $attendance->update(['time_out' => $now]);
            return response()->json([
                'status' => 'success',
                'type' => 'clock_out',
                'message' => "Goodbye, $name!"
            ]);

        } else {
            // --- TIME IN LOGIC ---
            
            $status = 'Present';
            
            // Employee Late Logic
            if ($type === 'App\Models\Employee' && $person->schedule) {
                $scheduledTime = Carbon::parse($person->schedule->time_in);
                if ($now->gt($scheduledTime->addMinutes(15))) {
                    $status = 'Late';
                }
            }
            // Student Late Logic
            elseif ($type === 'App\Models\Student') {
                 if ($now->format('H:i') > '07:30') { 
                     $status = 'Late';
                 }
            }

            Attendance::create([
                'attendable_id' => $person->id,
                'attendable_type' => $type,
                'date' => $date,
                'time_in' => $now,
                'status' => $status
            ]);

            return response()->json([
                'status' => 'success',
                'type' => 'clock_in',
                'message' => "Welcome, $name!"
            ]);
        }
    }
    

    // --- 4. EXPORT CSV (EMPLOYEES ONLY) ---
    public function exportEmployees() {
        $fileName = 'employee_attendance_' . date('Y-m-d') . '.csv';
        $logs = Attendance::with('attendable.user')
                    ->where('attendable_type', 'App\Models\Employee')
                    ->orderBy('date', 'desc')
                    ->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Employee Name', 'Date', 'Time In', 'Time Out', 'Status');

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                $row['Employee Name']  = $log->attendable->user->name ?? 'Unknown';
                $row['Date']    = $log->date;
                $row['Time In'] = \Carbon\Carbon::parse($log->time_in)->format('h:i A');
                $row['Time Out'] = $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : '--';
                $row['Status']  = $log->status;

                fputcsv($file, array($row['Employee Name'], $row['Date'], $row['Time In'], $row['Time Out'], $row['Status']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // --- 5. GENERATE PDF REPORT (INDIVIDUAL) ---
    public function generateReport(Request $request) {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $employee = Employee::with('user')->findOrFail($request->employee_id);
        
        $logs = Attendance::where('attendable_id', $employee->id)
                          ->where('attendable_type', 'App\Models\Employee')
                          ->whereBetween('date', [$request->start_date, $request->end_date])
                          ->orderBy('date', 'asc')
                          ->get();

        $totalPresent = $logs->whereIn('status', ['Present', 'Late'])->count();
        $totalLates   = $logs->where('status', 'Late')->count();

        $pdf = Pdf::loadView('attendance.report_pdf', compact('employee', 'logs', 'request', 'totalPresent', 'totalLates'));
        
        return $pdf->download("Attendance_{$employee->user->name}_{$request->start_date}.pdf");
    }

    // --- 6. UPDATE ATTENDANCE (THE MISSING FUNCTION) ---
    public function update(Request $request, $id) {
        $log = Attendance::findOrFail($id);

        $request->validate([
            'time_in'  => 'required',
            'time_out' => 'nullable',
            'status'   => 'required|in:Present,Late,Absent,Half Day',
        ]);

        // Combine date with time inputs to create full timestamps
        $date = $log->date; 
        
        $log->update([
            'time_in'  => $date . ' ' . $request->time_in,
            'time_out' => $request->time_out ? $date . ' ' . $request->time_out : null,
            'status'   => $request->status,
        ]);

        return redirect()->back()->with('message', 'Attendance record updated successfully.');
    }
}