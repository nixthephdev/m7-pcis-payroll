<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
@media only screen and (max-width: 600px) {
.inner-body { width: 100% !important; }
.footer { width: 100% !important; }
}
@media only screen and (max-width: 500px) {
.button { width: 100% !important; }
}

body, body *:not(html):not(style):not(br):not(tr):not(code) {
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
    position: relative;
}
body {
    -webkit-text-size-adjust: none;
    background-color: #ffffff;
    color: #718096;
    height: 100%;
    line-height: 1.4;
    margin: 0;
    padding: 0;
    width: 100% !important;
}
p, ul, ol, blockquote { line-height: 1.4; text-align: left; }
a { color: #3869d4; }
a img { border: none; }
h1 { color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left; }
h2 { font-size: 16px; font-weight: bold; margin-top: 0; text-align: left; }
h3 { font-size: 14px; font-weight: bold; margin-top: 0; text-align: left; }
p { font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left; }
p.sub { font-size: 12px; }
img { max-width: 100%; }
.wrapper {
    -premailer-cellpadding: 0;
    -premailer-cellspacing: 0;
    -premailer-width: 100%;
    background-color: #edf2f7;
    margin: 0;
    padding: 0;
    width: 100%;
}
.content {
    -premailer-cellpadding: 0;
    -premailer-cellspacing: 0;
    -premailer-width: 100%;
    margin: 0;
    padding: 0;
    width: 100%;
}
.header { padding: 25px 0; text-align: center; }
.header a { color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; }
.body {
    -premailer-cellpadding: 0;
    -premailer-cellspacing: 0;
    -premailer-width: 100%;
    background-color: #edf2f7;
    border-bottom: 1px solid #edf2f7;
    border-top: 1px solid #edf2f7;
    margin: 0;
    padding: 0;
    width: 100%;
}
.inner-body {
    -premailer-cellpadding: 0;
    -premailer-cellspacing: 0;
    -premailer-width: 570px;
    background-color: #ffffff;
    border-color: #e8e5ef;
    border-radius: 2px;
    border-width: 1px;
    box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015);
    margin: 0 auto;
    padding: 0;
    width: 570px;
}
.content-cell { max-width: 100vw; padding: 32px; }
.footer {
    -premailer-cellpadding: 0;
    -premailer-cellspacing: 0;
    -premailer-width: 570px;
    margin: 0 auto;
    padding: 0;
    text-align: center;
    width: 570px;
}
.footer p { color: #b0adc5; font-size: 12px; text-align: center; }
.footer a { color: #b0adc5; text-decoration: underline; }
.details-table { margin: 20px 0 24px; width: 100%; border-collapse: collapse; }
.details-table td { color: #74787e; font-size: 15px; line-height: 18px; padding: 10px 0; border-bottom: 1px solid #edeff2; }
.details-table td:first-child { font-weight: bold; color: #3d4852; width: 42%; }
.alert-panel {
    border-left: 4px solid #2d3748;
    margin: 21px 0;
    background-color: #edf2f7;
    color: #718096;
    padding: 16px;
    font-size: 15px;
    line-height: 1.5em;
}
.alert-panel.absent { border-left-color: #e53e3e; }
.alert-panel.late { border-left-color: #d69e2e; }
</style>
</head>
<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">

{{-- Header --}}
<tr>
<td class="header">
<a href="{{ config('app.url') }}" style="display: inline-block;">
M7 PCIS Attendance &amp; Payroll
</a>
</td>
</tr>

{{-- Body --}}
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell">

    <h1>Attendance Notification</h1>

    <p>Dear <strong>{{ $student->guardian_name }}</strong>,</p>

    <p>
        This is an automated notification regarding the attendance of your child
        <strong>{{ $student->full_name }}</strong> ({{ $student->student_id }}) for
        <strong>{{ \Carbon\Carbon::parse($attendance->date)->format('l, F j, Y') }}</strong>.
    </p>

    <table class="details-table" role="presentation">
        <tr>
            <td>Student</td>
            <td>{{ $student->full_name }}</td>
        </tr>
        <tr>
            <td>Student ID</td>
            <td>{{ $student->student_id }}</td>
        </tr>
        <tr>
            <td>Grade &amp; Section</td>
            <td>{{ $student->grade_level }} &mdash; {{ $student->section }}</td>
        </tr>
        <tr>
            <td>Date</td>
            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M j, Y') }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td><strong>{{ $clockType === 'clock_out' ? 'Clocked Out' : $attendance->status }}</strong></td>
        </tr>
        @if($attendance->time_in)
        <tr>
            <td>Time In</td>
            <td>{{ \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') }}</td>
        </tr>
        @endif
        @if($clockType === 'clock_out' && $attendance->time_out)
        <tr>
            <td>Time Out</td>
            <td>{{ \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}</td>
        </tr>
        @endif
        @if($attendance->tardy_minutes > 0)
        <tr>
            <td>Minutes Late</td>
            <td>{{ $attendance->tardy_minutes }} min</td>
        </tr>
        @endif
    </table>

    @if($clockType === 'clock_out')
    <div class="alert-panel" style="border-left-color: #38a169;">
        Your child has safely clocked out at <strong>{{ \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}</strong>.
    </div>
    @elseif($attendance->status === 'Absent')
    <div class="alert-panel absent">
        Your child was not recorded as present today. If you believe this is an error or have notified the school of an absence, please disregard this message.
    </div>
    @elseif($attendance->status === 'Late')
    <div class="alert-panel late">
        Your child arrived <strong>{{ $attendance->tardy_minutes }} minute(s) late</strong>. Please remind them of the 8:00 AM check-in time.
    </div>
    @endif

    <p>If you have any questions, please contact the school administration directly.</p>

    <p>
        Regards,<br>
        M7 PCIS Attendance &amp; Payroll
    </p>

</td>
</tr>
</table>
</td>
</tr>

{{-- Footer --}}
<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center">
<p>&copy; {{ date('Y') }} M7 PCIS Attendance &amp; Payroll. All rights reserved.</p>
</td>
</tr>
</table>
</td>
</tr>

</table>
</td>
</tr>
</table>

</body>
</html>
