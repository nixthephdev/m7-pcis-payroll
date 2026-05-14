<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        $leaves = LeaveRequest::where('employee_id', $employee->id)
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('leaves.index', compact('leaves', 'employee'));
    }

    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        // Incentive Hours leave uses time fields instead of date range
        $isIncentive = $request->leave_type === 'Incentive Hours';

        $rules = [
            'leave_type' => 'required|string',
            'reason'     => 'required|string',
        ];

        if ($isIncentive) {
            $rules['start_date']  = 'required|date';
            $rules['start_time']  = 'required';
            $rules['end_time']    = 'required|after:start_time';
        } else {
            $rules['start_date']  = 'required|date';
            $rules['end_date']    = 'required|date|after_or_equal:start_date';
        }

        $request->validate($rules);

        // Solo Parent eligibility gate
        if ($request->leave_type === 'Solo Parent Leave' && !$employee->is_solo_parent) {
            return redirect()->back()->with('error', 'You are not registered as a Solo Parent.');
        }

        $startTime = null;
        $endTime   = null;
        $totalHours = null;
        $endDate   = $request->start_date;

        if ($isIncentive) {
            $startTime  = $request->start_time;
            $endTime    = $request->end_time;
            $start      = Carbon::parse($request->start_date . ' ' . $startTime);
            $end        = Carbon::parse($request->start_date . ' ' . $endTime);
            $totalHours = round($end->diffInMinutes($start) / 60, 2);

            if ($employee->incentive_hours_credits < $totalHours) {
                return redirect()->back()->with('error', "Not enough Incentive Hours credits! You have {$employee->incentive_hours_credits} hrs.");
            }
        } else {
            $daysRequested = Carbon::parse($request->start_date)->diffInDays($request->end_date) + 1;
            $endDate = $request->end_date;

            $creditChecks = [
                'Vacation Leave'    => ['field' => 'vacation_credits',         'label' => 'Vacation Leave'],
                'Sick Leave'        => ['field' => 'sick_credits',             'label' => 'Sick Leave'],
                'Birthday Leave'    => ['field' => 'birthday_leave_credits',   'label' => 'Birthday Leave'],
                'Solo Parent Leave' => ['field' => 'solo_parent_leave_credits','label' => 'Solo Parent Leave'],
            ];

            if (isset($creditChecks[$request->leave_type])) {
                $check = $creditChecks[$request->leave_type];
                if ($employee->{$check['field']} < $daysRequested) {
                    return redirect()->back()->with('error', "Not enough {$check['label']} credits!");
                }
            }
        }

        LeaveRequest::create([
            'employee_id'  => $employee->id,
            'leave_type'   => $request->leave_type,
            'start_date'   => $request->start_date,
            'end_date'     => $endDate,
            'start_time'   => $startTime,
            'end_time'     => $endTime,
            'total_hours'  => $totalHours,
            'reason'       => $request->reason,
            'status'       => 'Pending',
        ]);

        return redirect()->back()->with('message', 'Leave request submitted successfully.');
    }

    public function manage()
    {
        $leaves = LeaveRequest::with('employee.user')
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('leaves.manage', compact('leaves'));
    }

    public function updateStatus(Request $request, $id)
    {
        $leave    = LeaveRequest::findOrFail($id);
        $employee = $leave->employee;

        if ($request->status === 'Approved' && $leave->status !== 'Approved') {

            if ($leave->leave_type === 'Incentive Hours') {
                $hours = $leave->total_hours ?? 0;
                if ($employee->incentive_hours_credits < $hours) {
                    return redirect()->back()->with('error', 'Cannot approve: Insufficient Incentive Hours credits.');
                }
                $employee->decrement('incentive_hours_credits', $hours);

            } else {
                $days = Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1;

                $deductions = [
                    'Vacation Leave'    => 'vacation_credits',
                    'Sick Leave'        => 'sick_credits',
                    'Birthday Leave'    => 'birthday_leave_credits',
                    'Solo Parent Leave' => 'solo_parent_leave_credits',
                ];

                if (isset($deductions[$leave->leave_type])) {
                    $field = $deductions[$leave->leave_type];
                    if ($employee->$field < $days) {
                        return redirect()->back()->with('error', 'Cannot approve: Insufficient credits.');
                    }
                    $employee->decrement($field, $days);
                }
                // Maternity, Paternity, Official Business, Bereavement — no credit deduction
            }
        }

        $leave->update([
            'status'  => $request->status,
            'is_paid' => $request->has('is_paid') ? 1 : 0,
        ]);

        \App\Models\AuditLog::record(
            'Leave Decision',
            'Set leave status to ' . $request->status . ' for ' . $employee->user->name
        );

        return redirect()->back()->with('message', 'Leave request updated.');
    }

    public function teamApprovals()
    {
        $user = Auth::user();

        if (!$user->employee || $user->employee->subordinates->isEmpty()) {
            abort(403, 'You are not a designated Supervisor/Coordinator.');
        }

        $leaves = LeaveRequest::whereIn('employee_id', $user->employee->subordinates->pluck('id'))
                              ->where('supervisor_status', 'Pending')
                              ->with('employee.user')
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('leaves.team', compact('leaves'));
    }

    public function supervisorAction(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);

        if ($request->action === 'Approve') {
            $leave->update(['supervisor_status' => 'Approved']);
            return redirect()->back()->with('message', 'Endorsed to HR for final approval.');
        } else {
            $leave->update([
                'supervisor_status' => 'Rejected',
                'status'            => 'Rejected',
            ]);
            return redirect()->back()->with('message', 'Leave request rejected.');
        }
    }
}
