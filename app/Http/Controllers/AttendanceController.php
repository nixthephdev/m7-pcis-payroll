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
    public function scan(Request $request)
    {
        $id = $request->input('employee_id');

        // 1. Search in Employees
        $person = Employee::where('employee_code', $id)->first();
        $type = 'App\Models\Employee';

        // 2. If not found, Search in Students
        if (!$person) {
            $person = \App\Models\Student::where('student_id', $id)->first();
            $type = 'App\Models\Student';
        }

        // 3. If ID not found anywhere
        if (!$person) {
            return response()->json(['status' => 'error', 'message' => 'ID Number not found.']);
        }

        // 4. GET NAME SAFELY (The Fix)
        $name = 'Unknown';
        
        if ($type === 'App\Models\Employee') {
            // Employees have a User account
            $name = $person->user ? $person->user->name : 'Unknown Employee';
        } else {
            // Students: Try different column names to be safe
            if (!empty($person->first_name)) {
                $name = $person->first_name . ' ' . ($person->last_name ?? '');
            } elseif (!empty($person->name)) {
                $name = $person->name;
            } elseif (!empty($person->full_name)) {
                $name = $person->full_name;
            } else {
                $name = 'Student #' . $person->student_id;
            }
        }

        // 5. Check Time Logic
        $now = Carbon::now();
        $date = $now->format('Y-m-d');

        $attendance = Attendance::where('attendable_id', $person->id)
                                ->where('attendable_type', $type)
                                ->where('date', $date)
                                ->first();

        if ($attendance) {
            // TIME OUT
            if ($attendance->time_out) {
                return response()->json(['status' => 'error', 'message' => 'Already timed out today!']);
            }
            $attendance->update(['time_out' => $now]);
            return response()->json(['status' => 'success', 'message' => "Goodbye, $name!"]);
        } else {
            // TIME IN
            $status = 'Present';
            
            // Late Logic (Only for Employees with schedules)
            if ($type === 'App\Models\Employee' && $person->schedule) {
                $scheduledTime = Carbon::parse($person->schedule->time_in);
                if ($now->gt($scheduledTime->addMinutes(15))) {
                    $status = 'Late';
                }
            }
            // Students are marked Late if after 7:30 AM (Example rule)
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

            return response()->json(['status' => 'success', 'message' => "Welcome, $name!"]);
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
    // --- ADMIN: ATTENDANCE MANAGEMENT ---
    public function index(Request $request) {
        // 1. Determine Type (Default to 'employee')
        $type = $request->get('type', 'employee');

        // 2. Build Query
        $query = Attendance::with('attendable'); // <--- FIX: Removed .user

        if ($type === 'student') {
            $query->where('attendable_type', 'App\Models\Student');
        } else {
            $query->where('attendable_type', 'App\Models\Employee');
        }

        // 3. Get Results
        $attendances = $query->orderBy('date', 'desc')
                             ->orderBy('time_in', 'desc')
                             ->get();

        return view('attendance.index', compact('attendances', 'type'));
    }

    // --- EXPORT EMPLOYEE ATTENDANCE (CSV) ---
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

    // --- GENERATE INDIVIDUAL PDF REPORT ---
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

        // Calculate Stats
        $totalPresent = $logs->whereIn('status', ['Present', 'Late'])->count();
        $totalLates   = $logs->where('status', 'Late')->count();

        // Load PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('attendance.report_pdf', compact('employee', 'logs', 'request', 'totalPresent', 'totalLates'));
        
        return $pdf->download("Attendance_{$employee->user->name}_{$request->start_date}.pdf");
    }
}
