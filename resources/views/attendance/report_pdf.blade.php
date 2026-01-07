<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #312e81; padding-bottom: 10px; }
        .company-name { font-size: 18px; font-weight: bold; color: #312e81; }
        .report-title { font-size: 14px; font-weight: bold; margin-top: 5px; text-transform: uppercase; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-label { font-weight: bold; color: #666; }
        
        .logs-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .logs-table th { background-color: #f3f4f6; text-align: left; padding: 8px; border-bottom: 2px solid #ddd; font-size: 10px; uppercase; }
        .logs-table td { padding: 8px; border-bottom: 1px solid #eee; }
        
        .status-late { color: #dc2626; font-weight: bold; }
        .status-present { color: #059669; font-weight: bold; }
        
        .summary-box { margin-top: 30px; border: 1px solid #ddd; padding: 15px; width: 50%; }
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
                <span class="info-label">Employee Name:</span><br>
                <strong style="font-size: 14px;">{{ $employee->user->name }}</strong><br>
                <small>{{ $employee->position }}</small>
            </td>
            <td width="50%" style="text-align: right;">
                <span class="info-label">Report Period:</span><br>
                <strong>{{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</strong> to 
                <strong>{{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</strong>
            </td>
        </tr>
    </table>

    <table class="logs-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Day</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Duration</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            @php
                $in = \Carbon\Carbon::parse($log->time_in);
                $out = $log->time_out ? \Carbon\Carbon::parse($log->time_out) : null;
                $duration = $out ? $out->diff($in)->format('%Hh %Im') : '--';
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($log->date)->format('l') }}</td>
                <td>{{ $in->format('h:i A') }}</td>
                <td>{{ $out ? $out->format('h:i A') : '--' }}</td>
                <td>{{ $duration }}</td>
                <td>
                    <span class="{{ $log->status == 'Late' ? 'status-late' : 'status-present' }}">
                        {{ $log->status }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($logs->isEmpty())
        <p style="text-align: center; margin-top: 20px; color: #999;">No attendance records found for this period.</p>
    @endif

    <div class="summary-box">
        <strong>Summary:</strong><br><br>
        Total Days Present: {{ $totalPresent }}<br>
        Total Lates: {{ $totalLates }}
    </div>

</body>
</html>