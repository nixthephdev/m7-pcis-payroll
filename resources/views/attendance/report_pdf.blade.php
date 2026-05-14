<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #312e81; padding-bottom: 10px; }
        .company-name { font-size: 16px; font-weight: bold; color: #312e81; }
        .report-title { font-size: 12px; font-weight: bold; margin-top: 4px; text-transform: uppercase; letter-spacing: 1px; }
        .info-table { width: 100%; margin-bottom: 16px; }
        .info-label { font-weight: bold; color: #666; font-size: 10px; text-transform: uppercase; }
        .logs-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .logs-table th { background-color: #eef2ff; text-align: left; padding: 7px 8px; border-bottom: 2px solid #c7d2fe; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        .logs-table td { padding: 6px 8px; border-bottom: 1px solid #f0f0f0; }
        .logs-table tr:nth-child(even) td { background: #fafafa; }
        .status-late { color: #dc2626; font-weight: bold; }
        .status-present { color: #059669; font-weight: bold; }
        .status-absent { color: #6b7280; }
        .leave-row td { background: #f0fdf4 !important; color: #15803d; font-style: italic; }
        .summary-grid { display: table; width: 60%; margin-top: 24px; border: 1px solid #e0e0e0; border-collapse: collapse; }
        .summary-grid td { padding: 7px 12px; border: 1px solid #e0e0e0; }
        .summary-label { font-weight: bold; color: #555; background: #f9f9f9; width: 55%; }
        .summary-value { font-weight: bold; color: #312e81; }
        .section-title { font-size: 10px; font-weight: bold; color: #312e81; text-transform: uppercase; letter-spacing: 1px; margin: 18px 0 6px; }
    </style>
</head>
<body>

<div class="header">
    <div class="company-name">M7 Philippine Cambridge International School</div>
    <div class="report-title">Employee Attendance Report</div>
</div>

<table class="info-table">
    <tr>
        <td width="50%">
            <span class="info-label">Employee</span><br>
            <strong style="font-size:13px;">{{ $employee->user->name }}</strong><br>
            <small style="color:#888;">{{ $employee->position }}</small>
            @if($employee->schedule)
                <br><small style="color:#888;">Schedule: {{ $employee->schedule->name }} ({{ \Carbon\Carbon::parse($employee->schedule->time_in)->format('h:i A') }} – {{ \Carbon\Carbon::parse($employee->schedule->time_out)->format('h:i A') }})</small>
            @endif
        </td>
        <td width="50%" style="text-align:right;">
            <span class="info-label">Report Period</span><br>
            <strong>{{ \Carbon\Carbon::parse($request->start_date)->format('F d, Y') }}</strong>
            &nbsp;to&nbsp;
            <strong>{{ \Carbon\Carbon::parse($request->end_date)->format('F d, Y') }}</strong><br>
            <small style="color:#888;">Generated: {{ now()->format('M d, Y h:i A') }}</small>
        </td>
    </tr>
</table>

<div class="section-title">Daily Attendance Log</div>

<table class="logs-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Day</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Duration*</th>
            <th>Tardy (min)</th>
            <th>OT (min)</th>
            <th>OT Type</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Build a map of approved leave dates for quick lookup
            $leaveDates = [];
            foreach ($approvedLeaves as $lv) {
                if ($lv->leave_type === 'Incentive Hours') {
                    $leaveDates[$lv->start_date] = $lv->leave_type;
                } else {
                    $cur = \Carbon\Carbon::parse($lv->start_date);
                    $end = \Carbon\Carbon::parse($lv->end_date);
                    while ($cur->lte($end)) {
                        $leaveDates[$cur->format('Y-m-d')] = $lv->leave_type;
                        $cur->addDay();
                    }
                }
            }
        @endphp

        @foreach($logs as $log)
        @php
            $in  = \Carbon\Carbon::parse($log->time_in);
            $out = $log->time_out ? \Carbon\Carbon::parse($log->time_out) : null;
            $workedMins = $out ? $in->diffInMinutes($out) : 0;
            $netMins    = $workedMins > 60 ? $workedMins - 60 : $workedMins;
            $durLabel   = $out ? floor($netMins/60).'h '.($netMins%60).'m' : '--';
            $hasLeave   = isset($leaveDates[$log->date]);
        @endphp
        <tr class="{{ $hasLeave ? 'leave-row' : '' }}">
            <td>{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($log->date)->format('D') }}</td>
            <td>{{ $in->format('h:i A') }}</td>
            <td>{{ $out ? $out->format('h:i A') : '--' }}</td>
            <td>{{ $durLabel }}</td>
            <td style="text-align:center;">{{ $log->tardy_minutes > 0 ? $log->tardy_minutes : '--' }}</td>
            <td style="text-align:center;">{{ $log->overtime_minutes > 0 ? $log->overtime_minutes : '--' }}</td>
            <td>{{ $log->overtime_type ?? '--' }}</td>
            <td>
                <span class="{{ $log->status === 'Late' ? 'status-late' : ($log->status === 'Present' ? 'status-present' : 'status-absent') }}">
                    {{ $log->status }}
                </span>
                @if($hasLeave)
                    <span style="font-size:9px;color:#15803d;">(On Leave)</span>
                @endif
            </td>
        </tr>
        @endforeach

        @if($logs->isEmpty())
        <tr><td colspan="9" style="text-align:center;color:#999;padding:16px;">No attendance records for this period.</td></tr>
        @endif
    </tbody>
</table>

<p style="font-size:9px;color:#aaa;margin-top:4px;">* Duration excludes 1-hour lunch break.</p>

@if($approvedLeaves->isNotEmpty())
<div class="section-title">Approved Leaves in Period</div>
<table class="logs-table">
    <thead>
        <tr><th>Leave Type</th><th>Period</th><th>Paid/Unpaid</th></tr>
    </thead>
    <tbody>
        @foreach($approvedLeaves as $lv)
        <tr>
            <td>{{ $lv->leave_type }}</td>
            <td>
                @if($lv->leave_type === 'Incentive Hours')
                    {{ \Carbon\Carbon::parse($lv->start_date)->format('M d, Y') }}
                    ({{ \Carbon\Carbon::parse($lv->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($lv->end_time)->format('h:i A') }}, {{ $lv->total_hours }}h)
                @else
                    {{ \Carbon\Carbon::parse($lv->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($lv->end_date)->format('M d, Y') }}
                @endif
            </td>
            <td>{{ $lv->is_paid ? 'Paid' : 'Unpaid' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="section-title">Summary</div>
<table class="summary-grid">
    <tr><td class="summary-label">Days Present</td><td class="summary-value">{{ $totalPresent }}</td></tr>
    <tr><td class="summary-label">Days Late</td><td class="summary-value">{{ $totalLates }}</td></tr>
    <tr><td class="summary-label">Total Tardy (minutes)</td><td class="summary-value">{{ $totalTardy }}</td></tr>
    <tr><td class="summary-label">Total Undertime (minutes)</td><td class="summary-value">{{ $totalUndertime }}</td></tr>
    <tr><td class="summary-label">Total Regular Day OT (minutes)</td><td class="summary-value">{{ $otRegularDay }}</td></tr>
    <tr><td class="summary-label">Total Regular Holiday OT (minutes)</td><td class="summary-value">{{ $otHoliday }}</td></tr>
    <tr><td class="summary-label">Total Rest Day / Special Holiday OT (minutes)</td><td class="summary-value">{{ $otRestDay }}</td></tr>
    <tr><td class="summary-label">Total Vacation Leave Day/s</td><td class="summary-value">{{ $totalVLDays }}</td></tr>
    <tr><td class="summary-label">Total Sick Leave Day/s</td><td class="summary-value">{{ $totalSLDays }}</td></tr>
    <tr><td class="summary-label">Total Unpaid Leave Day/s</td><td class="summary-value">{{ $totalUnpaidDays }}</td></tr>
    <tr><td class="summary-label">Approved Leaves</td><td class="summary-value">{{ $approvedLeaves->count() }}</td></tr>
</table>

</body>
</html>
