<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Attendance Notification — M7 PCIS</title>
    <style>
        @media only screen and (max-width: 600px) {
            .email-wrapper { width: 100% !important; }
            .email-body    { width: 100% !important; padding: 24px 16px !important; }
            .header-cell   { padding: 28px 16px !important; }
            .detail-label,
            .detail-value  { display: block !important; width: 100% !important; }
        }
        * { box-sizing: border-box; }
        body {
            margin: 0; padding: 0;
            background-color: #eef1f8;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            -webkit-text-size-adjust: none;
            color: #3d4852;
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#eef1f8;">

{{-- Outer wrapper --}}
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#eef1f8;margin:0;padding:32px 0;">
<tr><td align="center">

    {{-- Email card (max 600px) --}}
    <table class="email-wrapper" width="600" cellpadding="0" cellspacing="0" role="presentation"
           style="width:600px;max-width:600px;">

        {{-- ═══════════════════════════════════════════
             HEADER — Deep navy with school logo + IB tag
             ═══════════════════════════════════════════ --}}
        <tr>
            <td class="header-cell" align="center"
                style="background-color:#1e3a8a;padding:36px 40px 28px;border-radius:12px 12px 0 0;">

                {{-- Logos row: School logo + IB World School logo --}}
                <table cellpadding="0" cellspacing="0" role="presentation" style="margin:0 auto 14px;">
                    <tr>
                        <td align="center" style="padding-right:18px;">
                            <img src="{{ config('app.url') }}/images/logo.png"
                                 alt="M7 PCIS Logo"
                                 height="68"
                                 style="display:block;"/>
                        </td>
                        <td style="width:1px;background:rgba(245,194,0,0.5);height:68px;">&nbsp;</td>
                        <td align="center" style="padding-left:18px;">
                            <img src="{{ config('app.url') }}/images/world.webp"
                                 alt="IB World School"
                                 height="68"
                                 style="display:block;"/>
                        </td>
                    </tr>
                </table>

                {{-- School name --}}
                <div style="font-size:20px;font-weight:800;color:#ffffff;letter-spacing:0.5px;line-height:1.2;">
                    M7 Philippine Cambridge<br>International School
                </div>

                {{-- Yellow divider --}}
                <div style="width:56px;height:3px;background:#f5c200;margin:14px auto 12px;border-radius:2px;"></div>

                {{-- IB tag line --}}
                <div style="display:inline-block;background:rgba(245,194,0,0.15);border:1px solid rgba(245,194,0,0.5);
                            color:#f5c200;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;
                            padding:4px 14px;border-radius:20px;">
                    IB World School
                </div>
            </td>
        </tr>

        {{-- ═══════════════════════════════════════════
             NOTIFICATION TYPE BANNER
             ═══════════════════════════════════════════ --}}
        @php
            if ($clockType === 'clock_out') {
                $bannerBg    = '#16a34a'; // logo green
                $bannerText  = '#ffffff';
                $bannerIcon  = '✓';
                $bannerLabel = 'Clock-Out Recorded';
            } elseif ($attendance->status === 'Absent') {
                $bannerBg    = '#dc2626'; // logo red
                $bannerText  = '#ffffff';
                $bannerIcon  = '!';
                $bannerLabel = 'Absence Notice';
            } elseif ($attendance->status === 'Late') {
                $bannerBg    = '#b45309'; // dark amber from logo yellow
                $bannerText  = '#ffffff';
                $bannerIcon  = '⏰';
                $bannerLabel = 'Late Arrival';
            } else {
                $bannerBg    = '#1e3a8a'; // logo royal blue
                $bannerText  = '#f5c200'; // logo yellow text on blue
                $bannerIcon  = '✓';
                $bannerLabel = 'Clock-In Recorded';
            }
        @endphp
        <tr>
            <td align="center"
                style="background-color:{{ $bannerBg }};padding:12px 40px;">
                <span style="color:{{ $bannerText }};font-size:13px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
                    {{ $bannerIcon }}&nbsp;&nbsp;{{ $bannerLabel }}
                </span>
            </td>
        </tr>

        {{-- ═══════════════════════════════════════════
             BODY
             ═══════════════════════════════════════════ --}}
        <tr>
            <td class="email-body"
                style="background-color:#ffffff;padding:36px 40px 28px;border-left:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">

                {{-- Greeting --}}
                <p style="font-size:16px;color:#2d3748;font-weight:600;margin:0 0 8px;">
                    Dear {{ $student->guardian_name }},
                </p>
                <p style="font-size:14px;color:#718096;margin:0 0 28px;line-height:1.6;">
                    This is an automated attendance notification from
                    <strong style="color:#14213d;">M7 PCIS</strong>
                    regarding your child's school attendance.
                </p>

                {{-- Student info card --}}
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                       style="background:#f7f9fc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:24px;overflow:hidden;">
                    <tr>
                        <td style="padding:14px 18px;border-bottom:1px solid #e2e8f0;background:#1e3a8a;">
                            <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#f5c200;">
                                Student Information
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 18px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="detail-label" style="font-size:12px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:0.8px;padding:7px 0;width:42%;border-bottom:1px solid #edf2f7;">Name</td>
                                    <td class="detail-value" style="font-size:14px;color:#2d3748;font-weight:600;padding:7px 0;border-bottom:1px solid #edf2f7;">{{ $student->full_name }}</td>
                                </tr>
                                <tr>
                                    <td class="detail-label" style="font-size:12px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:0.8px;padding:7px 0;width:42%;border-bottom:1px solid #edf2f7;">Student ID</td>
                                    <td class="detail-value" style="font-size:14px;color:#2d3748;font-weight:600;padding:7px 0;border-bottom:1px solid #edf2f7;">{{ $student->student_id }}</td>
                                </tr>
                                <tr>
                                    <td class="detail-label" style="font-size:12px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:0.8px;padding:7px 0;width:42%;border-bottom:1px solid #edf2f7;">Grade &amp; Section</td>
                                    <td class="detail-value" style="font-size:14px;color:#2d3748;font-weight:600;padding:7px 0;border-bottom:1px solid #edf2f7;">{{ $student->grade_level }} &mdash; {{ $student->section }}</td>
                                </tr>
                                <tr>
                                    <td class="detail-label" style="font-size:12px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:0.8px;padding:7px 0;width:42%;">Date</td>
                                    <td class="detail-value" style="font-size:14px;color:#2d3748;font-weight:600;padding:7px 0;">
                                        {{ \Carbon\Carbon::parse($attendance->date)->format('l, F j, Y') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                {{-- Attendance detail card --}}
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                       style="background:#f7f9fc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:24px;overflow:hidden;">
                    <tr>
                        <td style="padding:14px 18px;border-bottom:1px solid #e2e8f0;background:#1e3a8a;">
                            <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#f5c200;">
                                Attendance Record
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 18px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">

                                {{-- Status row --}}
                                <tr>
                                    <td class="detail-label" style="font-size:12px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:0.8px;padding:7px 0;width:42%;border-bottom:1px solid #edf2f7;">Status</td>
                                    <td class="detail-value" style="padding:7px 0;border-bottom:1px solid #edf2f7;">
                                        @if($clockType === 'clock_out')
                                            <span style="background:#d4edda;color:#155724;font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;">
                                                Clocked Out
                                            </span>
                                        @elseif($attendance->status === 'Absent')
                                            <span style="background:#f8d7da;color:#721c24;font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;">
                                                Absent
                                            </span>
                                        @elseif($attendance->status === 'Late')
                                            <span style="background:#fff3cd;color:#856404;font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;">
                                                Late
                                            </span>
                                        @else
                                            <span style="background:#cce5ff;color:#004085;font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;">
                                                Present
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                @if($attendance->time_in)
                                <tr>
                                    <td class="detail-label" style="font-size:12px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:0.8px;padding:7px 0;width:42%;border-bottom:1px solid #edf2f7;">Time In</td>
                                    <td class="detail-value" style="font-size:14px;color:#2d3748;font-weight:600;padding:7px 0;border-bottom:1px solid #edf2f7;">
                                        {{ \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') }}
                                    </td>
                                </tr>
                                @endif

                                @if($clockType === 'clock_out' && $attendance->time_out)
                                <tr>
                                    <td class="detail-label" style="font-size:12px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:0.8px;padding:7px 0;width:42%;border-bottom:1px solid #edf2f7;">Time Out</td>
                                    <td class="detail-value" style="font-size:14px;color:#2d3748;font-weight:600;padding:7px 0;border-bottom:1px solid #edf2f7;">
                                        {{ \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}
                                    </td>
                                </tr>
                                @endif

                                @if($attendance->tardy_minutes > 0)
                                <tr>
                                    <td class="detail-label" style="font-size:12px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:0.8px;padding:7px 0;width:42%;">Minutes Late</td>
                                    <td class="detail-value" style="font-size:14px;color:#b7791f;font-weight:700;padding:7px 0;">
                                        {{ $attendance->tardy_minutes }} minutes
                                    </td>
                                </tr>
                                @endif

                            </table>
                        </td>
                    </tr>
                </table>

                {{-- Contextual message --}}
                @if($clockType === 'clock_out')
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                       style="background:#f0fdf4;border-left:4px solid #16a34a;border-radius:0 6px 6px 0;margin-bottom:24px;">
                    <tr>
                        <td style="padding:16px 18px;font-size:14px;color:#14532d;line-height:1.6;">
                            Your child has <strong>safely clocked out</strong> at
                            <strong>{{ \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}</strong>.
                            We hope they have a safe journey home.
                        </td>
                    </tr>
                </table>
                @elseif($attendance->status === 'Absent')
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                       style="background:#fef2f2;border-left:4px solid #dc2626;border-radius:0 6px 6px 0;margin-bottom:24px;">
                    <tr>
                        <td style="padding:16px 18px;font-size:14px;color:#7f1d1d;line-height:1.6;">
                            Your child was <strong>not recorded as present</strong> today. If an absence was communicated
                            to the school in advance, please disregard this message. Otherwise, please contact the
                            school administration.
                        </td>
                    </tr>
                </table>
                @elseif($attendance->status === 'Late')
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                       style="background:#fefce8;border-left:4px solid #b45309;border-radius:0 6px 6px 0;margin-bottom:24px;">
                    <tr>
                        <td style="padding:16px 18px;font-size:14px;color:#78350f;line-height:1.6;">
                            Your child arrived <strong>{{ $attendance->tardy_minutes }} minute(s) late</strong> today.
                            School check-in begins at <strong>8:00 AM</strong>. Punctuality is an important part of
                            the IB learner profile.
                        </td>
                    </tr>
                </table>
                @else
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                       style="background:#eff6ff;border-left:4px solid #1e3a8a;border-radius:0 6px 6px 0;margin-bottom:24px;">
                    <tr>
                        <td style="padding:16px 18px;font-size:14px;color:#1e3a8a;line-height:1.6;">
                            Your child has <strong>successfully checked in</strong> and is present in school today.
                        </td>
                    </tr>
                </table>
                @endif

                {{-- Closing --}}
                <p style="font-size:14px;color:#718096;line-height:1.6;margin:0 0 6px;">
                    For any concerns, please contact the school administration office directly.
                </p>
                <p style="font-size:14px;color:#2d3748;margin:0;">
                    Warm regards,<br>
                    <strong style="color:#1e3a8a;">M7 PCIS Administration</strong>
                </p>

            </td>
        </tr>

        {{-- ═══════════════════════════════════════════
             FOOTER
             ═══════════════════════════════════════════ --}}
        <tr>
            <td align="center"
                style="background-color:#14213d;padding:24px 40px;border-radius:0 0 12px 12px;">

                {{-- Gold divider --}}
                <div style="width:40px;height:2px;background:#f5c200;margin:0 auto 16px;border-radius:2px;"></div>

                <p style="font-size:11px;color:#a0aec0;margin:0 0 6px;line-height:1.6;">
                    This is an automated message from the M7 PCIS Attendance System.<br>
                    Please do not reply to this email.
                </p>
                <p style="font-size:11px;color:#718096;margin:0;">
                    &copy; {{ date('Y') }} M7 Philippine Cambridge International School &mdash; IB World School
                </p>
            </td>
        </tr>

        {{-- Spacer --}}
        <tr><td style="height:32px;"></td></tr>

    </table>

</td></tr>
</table>

</body>
</html>
