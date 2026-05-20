<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Holiday;
use App\Mail\StudentAttendanceNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    // --- 1. ADMIN: ATTENDANCE MANAGEMENT ---
    public function index(Request $request)
    {
        $type     = $request->get('type', 'employee');
        $search   = $request->get('search', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo   = $request->get('date_to',   '');

        $query = Attendance::with('attendable');

        if ($type === 'student') {
            $query->where('attendable_type', 'App\Models\Student');

            if ($search) {
                $ids = Student::where(function ($q) use ($search) {
                    $q->where('full_name',  'LIKE', "%{$search}%")
                      ->orWhere('student_id', 'LIKE', "%{$search}%");
                })->pluck('id');
                $query->whereIn('attendable_id', $ids);
            }
        } else {
            $query->where('attendable_type', 'App\Models\Employee');

            if ($search) {
                $ids = Employee::where(function ($q) use ($search) {
                    $q->whereHas('user', fn($uq) => $uq->where('name', 'LIKE', "%{$search}%"))
                      ->orWhere('position', 'LIKE', "%{$search}%")
                      ->orWhere('employee_code', 'LIKE', "%{$search}%");
                })->pluck('id');
                $query->whereIn('attendable_id', $ids);
            }
        }

        if ($dateFrom && $dateTo) {
            $query->whereBetween('date', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        } elseif ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }

        $attendances = $query->orderBy('date', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(25)
                             ->withQueryString();

        return view('attendance.index', compact('attendances', 'type', 'search', 'dateFrom', 'dateTo'));
    }

    // --- 2. EMPLOYEE: VIEW OWN ATTENDANCE ---
    public function myAttendance(Request $request)
    {
        $employee = Auth::user()->employee;

        $month = $request->get('month', Carbon::now()->format('Y-m'));

        [$year, $mon] = explode('-', $month);

        $logs = Attendance::where('attendable_id', $employee->id)
                          ->where('attendable_type', 'App\Models\Employee')
                          ->whereYear('date', $year)
                          ->whereMonth('date', $mon)
                          ->orderBy('date', 'asc')
                          ->get();

        // Approved leaves for the month (to highlight in calendar)
        $approvedLeaves = LeaveRequest::where('employee_id', $employee->id)
                            ->where('status', 'Approved')
                            ->whereYear('start_date', $year)
                            ->whereMonth('start_date', $mon)
                            ->get();

        $totalPresent       = $logs->whereIn('status', ['Present', 'Late'])->count();
        $totalTardy         = $logs->sum('tardy_minutes');
        $totalUndertime     = $logs->sum('undertime_minutes');
        $totalOvertimeMins  = $logs->sum('overtime_minutes');

        return view('attendance.my_attendance', compact(
            'logs', 'employee', 'month', 'approvedLeaves',
            'totalPresent', 'totalTardy', 'totalUndertime', 'totalOvertimeMins'
        ));
    }

    // --- 3. KIOSK SCAN (QR CODE) ---
    public function scan(Request $request)
    {
        $id = $request->input('employee_id');

        $person = Employee::where('employee_code', $id)->first();
        $type   = 'App\Models\Employee';

        if (!$person) {
            $person = Student::where('student_id', $id)->first();
            $type   = 'App\Models\Student';
        }

        if (!$person) {
            return response()->json(['status' => 'error', 'message' => 'ID Number not found.']);
        }

        $name = $type === 'App\Models\Employee'
            ? ($person->user->name ?? 'Unknown Employee')
            : ($person->full_name ?? ($person->first_name . ' ' . $person->last_name));

        $now       = Carbon::now();
        $date      = $now->format('Y-m-d');
        $yesterday = $now->copy()->subDay()->format('Y-m-d');

        $attendance = Attendance::where('attendable_id', $person->id)
                                ->where('attendable_type', $type)
                                ->where('date', $date)
                                ->first();

        // Night shift: only for guards — if no record today and it's still early morning,
        // check if there's an open clock-in from yesterday (overnight shift).
        $isGuard = $type === 'App\Models\Employee' && ($person->user->role ?? '') === 'guard';
        if (!$attendance && $now->hour < 12 && $isGuard) {
            $nightShiftRecord = Attendance::where('attendable_id', $person->id)
                                          ->where('attendable_type', $type)
                                          ->where('date', $yesterday)
                                          ->whereNull('time_out')
                                          ->first();
            if ($nightShiftRecord) {
                $attendance = $nightShiftRecord;
                $date       = $yesterday; // use yesterday's date for undertime calc
            }
        }

        if ($attendance) {
            // TIME OUT
            if ($attendance->time_out) {
                return response()->json(['status' => 'error', 'message' => 'Already timed out today!']);
            }

            // Prevent double punch within 5 min
            if (Carbon::parse($attendance->time_in)->diffInMinutes($now) < 5) {
                return response()->json(['status' => 'error', 'message' => 'Please wait 5 minutes before clocking out.']);
            }

            $undertimeMinutes = 0;
            if ($type === 'App\Models\Employee' && $person->schedule) {
                $scheduledOut = Carbon::parse($date . ' ' . $person->schedule->time_out);
                // For overnight schedules, if scheduled out is before time_in, add a day
                if ($scheduledOut->lt(Carbon::parse($attendance->time_in))) {
                    $scheduledOut->addDay();
                }
                if ($now->lt($scheduledOut)) {
                    $undertimeMinutes = (int) $now->diffInMinutes($scheduledOut);
                }
            }

            $attendance->update([
                'time_out'          => $now,
                'undertime_minutes' => $undertimeMinutes,
            ]);

            if ($type === 'App\Models\Student' && $person->guardian_email) {
                try {
                    Mail::to($person->guardian_email)
                        ->send(new StudentAttendanceNotification($person, $attendance->fresh(), 'clock_out'));
                } catch (\Throwable $e) {
                    Log::error("Student attendance email failed for {$person->student_id}: " . $e->getMessage());
                }
            }

            return response()->json(['status' => 'success', 'type' => 'clock_out', 'message' => "Goodbye, $name!"]);

        } else {
            // TIME IN
            $status       = 'Present';
            $tardyMinutes = 0;

            if ($type === 'App\Models\Employee' && $person->schedule && !$person->schedule->is_flexible) {
                $scheduledIn  = Carbon::parse($date . ' ' . $person->schedule->time_in);
                $diffMinutes  = $scheduledIn->diffInMinutes($now, false); // positive = late

                if ($diffMinutes > 5) {
                    $status       = 'Late';
                    $tardyMinutes = (int) $diffMinutes;
                }

                // Guard protection: tardy > 6 hours on clock-in means the schedule's
                // time_in is misaligned with the actual shift (e.g. a night shift guard
                // clocking in at 5 PM when their stored time_in is an AM value).
                if ($isGuard && $tardyMinutes > 360) {
                    $status       = 'Present';
                    $tardyMinutes = 0;
                }
            } elseif ($type === 'App\Models\Student') {
                $cutoff      = Carbon::parse($date . ' 08:00');
                $diffMinutes = $cutoff->diffInMinutes($now, false);
                if ($diffMinutes > 5) {
                    $status       = 'Late';
                    $tardyMinutes = (int) $diffMinutes;
                }
            }

            $attendance = Attendance::create([
                'attendable_id'   => $person->id,
                'attendable_type' => $type,
                'date'            => $date,
                'time_in'         => $now,
                'status'          => $status,
                'tardy_minutes'   => $tardyMinutes,
            ]);

            if ($type === 'App\Models\Student' && $person->guardian_email) {
                try {
                    Mail::to($person->guardian_email)
                        ->send(new StudentAttendanceNotification($person, $attendance));
                } catch (\Throwable $e) {
                    Log::error("Student attendance email failed for {$person->student_id}: " . $e->getMessage());
                }
            }

            return response()->json(['status' => 'success', 'type' => 'clock_in', 'message' => "Welcome, $name!"]);
        }
    }

    // --- 4. ADMIN: UPDATE ATTENDANCE RECORD ---
    public function update(Request $request, $id)
    {
        $log = Attendance::findOrFail($id);

        $request->validate([
            'time_in'          => 'required',
            'time_out'         => 'nullable',
            'status'           => 'required|in:Present,Late,Absent,Half Day',
            'tardy_minutes'    => 'nullable|integer|min:0',
            'overtime_minutes' => 'nullable|integer|min:0',
            'overtime_type'    => 'nullable|string',
        ]);

        $date = $log->date;

        // Recompute undertime if both times present and employee has a schedule
        $undertimeMinutes = $log->undertime_minutes;
        if ($request->time_out && $log->attendable instanceof Employee && $log->attendable->schedule) {
            $scheduledOut     = Carbon::parse($date . ' ' . $log->attendable->schedule->time_out);
            $actualOut        = Carbon::parse($date . ' ' . $request->time_out);
            $undertimeMinutes = $actualOut->lt($scheduledOut) ? (int) $actualOut->diffInMinutes($scheduledOut) : 0;
        }

        $log->update([
            'time_in'           => $date . ' ' . $request->time_in,
            'time_out'          => $request->time_out ? $date . ' ' . $request->time_out : null,
            'status'            => $request->status,
            'tardy_minutes'     => $request->tardy_minutes ?? 0,
            'undertime_minutes' => $undertimeMinutes,
            'overtime_minutes'  => $request->overtime_minutes ?? 0,
            'overtime_type'     => $request->overtime_type ?: null,
        ]);

        return redirect()->back()->with('message', 'Attendance record updated successfully.');
    }

    // --- 5. EXPORT CSV ---
    public function exportEmployees()
    {
        $fileName = 'employee_attendance_' . date('Y-m-d') . '.csv';
        $logs = Attendance::with('attendable.user')
                    ->where('attendable_type', 'App\Models\Employee')
                    ->orderBy('date', 'desc')
                    ->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = ['Employee Name', 'Date', 'Time In', 'Time Out', 'Status', 'Tardy (min)', 'Undertime (min)', 'OT (min)', 'OT Type'];

        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->attendable->user->name ?? 'Unknown',
                    $log->date,
                    Carbon::parse($log->time_in)->format('h:i A'),
                    $log->time_out ? Carbon::parse($log->time_out)->format('h:i A') : '--',
                    $log->status,
                    $log->tardy_minutes,
                    $log->undertime_minutes,
                    $log->overtime_minutes,
                    $log->overtime_type ?? '--',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // --- 6. GENERATE PDF REPORT (Admin only) ---
    public function generateReport(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $employee = Employee::with('user', 'schedule')->findOrFail($request->employee_id);

        $logs = Attendance::where('attendable_id', $employee->id)
                          ->where('attendable_type', 'App\Models\Employee')
                          ->whereBetween('date', [$request->start_date, $request->end_date])
                          ->orderBy('date', 'asc')
                          ->get();

        $approvedLeaves = LeaveRequest::where('employee_id', $employee->id)
                            ->where('status', 'Approved')
                            ->whereBetween('start_date', [$request->start_date, $request->end_date])
                            ->get();

        $totalPresent      = $logs->whereIn('status', ['Present', 'Late'])->count();
        $totalAbsent       = $logs->where('status', 'Absent')->count();
        $totalTardy        = $logs->sum('tardy_minutes');
        $totalUndertime    = $logs->sum('undertime_minutes');
        $totalOvertimeMins = $logs->sum('overtime_minutes');
        $totalLates        = $logs->where('status', 'Late')->count();

        // OT breakdown by type
        $otRegularDay  = $logs->where('overtime_type', 'Regular Day')->sum('overtime_minutes');
        $otHoliday     = $logs->where('overtime_type', 'Regular Holiday')->sum('overtime_minutes');
        $otRestDay     = $logs->where('overtime_type', 'Rest Day / Special Holiday')->sum('overtime_minutes');

        // Leave day totals
        $totalVLDays     = $approvedLeaves->where('leave_type', 'Vacation Leave')->sum(fn($lv) =>
            \Carbon\Carbon::parse($lv->start_date)->diffInDaysFiltered(fn($d) => true, \Carbon\Carbon::parse($lv->end_date)) + 1
        );
        $totalSLDays     = $approvedLeaves->where('leave_type', 'Sick Leave')->sum(fn($lv) =>
            \Carbon\Carbon::parse($lv->start_date)->diffInDaysFiltered(fn($d) => true, \Carbon\Carbon::parse($lv->end_date)) + 1
        );
        $totalUnpaidDays = $approvedLeaves->where('is_paid', false)->where('leave_type', '!=', 'Incentive Hours')->sum(fn($lv) =>
            \Carbon\Carbon::parse($lv->start_date)->diffInDaysFiltered(fn($d) => true, \Carbon\Carbon::parse($lv->end_date)) + 1
        );

        // Build holiday map for the date range
        $holidayDates = [];
        $nonRecurringHolidays = Holiday::where('is_recurring', false)
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->get()
            ->keyBy(fn($h) => $h->date->format('Y-m-d'));
        $recurringHolidays = Holiday::where('is_recurring', true)->get();

        $cursor = Carbon::parse($request->start_date);
        $rangeEnd = Carbon::parse($request->end_date);
        while ($cursor->lte($rangeEnd)) {
            $key = $cursor->format('Y-m-d');
            if ($nonRecurringHolidays->has($key)) {
                $h = $nonRecurringHolidays[$key];
                $holidayDates[$key] = ['name' => $h->name, 'type' => $h->type];
            } else {
                $match = $recurringHolidays->first(
                    fn($h) => $h->date->month == $cursor->month && $h->date->day == $cursor->day
                );
                if ($match) {
                    $holidayDates[$key] = ['name' => $match->name, 'type' => $match->type];
                }
            }
            $cursor->addDay();
        }
        $totalHolidays = count($holidayDates);

        $pdf = Pdf::loadView('attendance.report_pdf', compact(
            'employee', 'logs', 'request', 'approvedLeaves',
            'totalPresent', 'totalAbsent', 'totalLates', 'totalTardy', 'totalUndertime', 'totalOvertimeMins',
            'otRegularDay', 'otHoliday', 'otRestDay',
            'totalVLDays', 'totalSLDays', 'totalUnpaidDays',
            'holidayDates', 'totalHolidays'
        ));

        return $pdf->download("Attendance_{$employee->user->name}_{$request->start_date}.pdf");
    }

    // --- 7. EDIT FORM (admin) ---
    public function edit($id)
    {
        $log = Attendance::with('attendable')->findOrFail($id);
        return view('attendance.edit', compact('log'));
    }

    // --- STUBS for manual clock-in/out buttons (if used in dashboard) ---
    public function clockIn(Request $request)
    {
        return redirect()->back()->with('message', 'Please use the QR kiosk to clock in.');
    }

    public function clockOut(Request $request)
    {
        return redirect()->back()->with('message', 'Please use the QR kiosk to clock out.');
    }
}
