<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Notification</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f6f9; font-family: Arial, sans-serif; color: #333; }
        .wrapper { max-width: 560px; margin: 32px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background-color: #4f46e5; padding: 28px 32px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; color: #ffffff; letter-spacing: 0.5px; }
        .header p { margin: 4px 0 0; font-size: 13px; color: #c7d2fe; }
        .body { padding: 32px; }
        .greeting { font-size: 15px; margin-bottom: 16px; }
        .message { font-size: 14px; color: #555; margin-bottom: 24px; line-height: 1.6; }
        .details-table { width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 24px; }
        .details-table td { padding: 10px 14px; border-bottom: 1px solid #f0f0f0; }
        .details-table td:first-child { font-weight: bold; color: #555; width: 40%; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-present  { background: #d1fae5; color: #065f46; }
        .badge-late     { background: #fef3c7; color: #92400e; }
        .badge-absent   { background: #fee2e2; color: #991b1b; }
        .badge-halfday  { background: #e0e7ff; color: #3730a3; }
        .footer { background-color: #f8f9fb; padding: 20px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
        .footer strong { color: #6b7280; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Student Attendance Notification</p>
        </div>

        <div class="body">
            <p class="greeting">Dear <strong>{{ $student->guardian_name }}</strong>,</p>

            <p class="message">
                This is an automated notification regarding the attendance of your child
                <strong>{{ $student->full_name }}</strong> ({{ $student->student_id }}) for
                <strong>{{ \Carbon\Carbon::parse($attendance->date)->format('l, F j, Y') }}</strong>.
            </p>

            <table class="details-table">
                <tr>
                    <td>Student</td>
                    <td>{{ $student->full_name }}</td>
                </tr>
                <tr>
                    <td>Student ID</td>
                    <td>{{ $student->student_id }}</td>
                </tr>
                <tr>
                    <td>Grade / Section</td>
                    <td>{{ $student->grade_level }} — {{ $student->section }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M j, Y') }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        @php
                            $badgeClass = match($attendance->status) {
                                'Present'  => 'badge-present',
                                'Late'     => 'badge-late',
                                'Absent'   => 'badge-absent',
                                'Half Day' => 'badge-halfday',
                                default    => 'badge-present',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $attendance->status }}</span>
                    </td>
                </tr>
                @if($attendance->time_in)
                <tr>
                    <td>Time In</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') }}</td>
                </tr>
                @endif
                @if($attendance->tardy_minutes > 0)
                <tr>
                    <td>Minutes Late</td>
                    <td>{{ $attendance->tardy_minutes }} min</td>
                </tr>
                @endif
            </table>

            @if($attendance->status === 'Absent')
            <p class="message" style="background:#fff7ed;border-left:3px solid #f97316;padding:10px 14px;border-radius:4px;">
                Your child was not recorded as present today. If you believe this is an error or have notified the school of an absence, please disregard this message.
            </p>
            @elseif($attendance->status === 'Late')
            <p class="message" style="background:#fffbeb;border-left:3px solid #f59e0b;padding:10px 14px;border-radius:4px;">
                Your child arrived <strong>{{ $attendance->tardy_minutes }} minute(s) late</strong>. Please remind them of the 8:00 AM check-in time.
            </p>
            @endif

            <p class="message" style="margin-bottom:0;">
                If you have any questions, please contact the school administration directly.
            </p>
        </div>

        <div class="footer">
            <strong>{{ config('app.name') }}</strong><br>
            This is an automated message — please do not reply to this email.
        </div>
    </div>
</body>
</html>
