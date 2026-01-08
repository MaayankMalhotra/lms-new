<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Webinar Registration</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background:#f3f4f6; color:#111827;">
    @php
        $startTime = $webinar->start_time ? \Carbon\Carbon::parse($webinar->start_time)->setTimezone('Asia/Kolkata') : null;
        $durationHours = is_numeric($webinar->duration) ? (float) $webinar->duration : null;
        $endTime = $startTime && $durationHours !== null
            ? (clone $startTime)->addMinutes((int) round($durationHours * 60))
            : null;
        $detailsUrl = route('webinars.show', $webinar->id);
    @endphp
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 24px auto; background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); overflow: hidden;">
        <tr>
            <td style="padding: 24px; text-align: center; background: #0c3c7c; color: #ffffff;">
                <h1 style="margin: 0; font-size: 24px; font-weight: bold;">Webinar Registration Confirmed</h1>
                <p style="margin: 8px 0 0; font-size: 14px; opacity: 0.9;">Thanks for registering, {{ $enrollment->name }}</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                <p style="margin: 0 0 16px; font-size: 16px;">You are registered for:</p>
                <h2 style="margin: 0 0 12px; font-size: 20px; color: #0c3c7c;">{{ $webinar->title }}</h2>
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 14px; color: #374151;">
                    <tr>
                        <td style="padding: 6px 0; width: 40%;">Webinar ID</td>
                        <td style="padding: 6px 0;">{{ $webinar->id }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0;">Date</td>
                        <td style="padding: 6px 0;">{{ $startTime ? $startTime->format('d M Y') : 'To be announced' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0;">Start Time</td>
                        <td style="padding: 6px 0;">{{ $startTime ? $startTime->format('h:i A') . ' IST' : 'To be announced' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0;">End Time</td>
                        <td style="padding: 6px 0;">{{ $endTime ? $endTime->format('h:i A') . ' IST' : 'To be announced' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0;">Duration</td>
                        <td style="padding: 6px 0;">{{ $durationHours !== null ? rtrim(rtrim(number_format($durationHours, 2), '0'), '.') . ' hour(s)' : 'To be announced' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0;">Entry</td>
                        <td style="padding: 6px 0;">{{ $webinar->entry_type ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0;">Topic</td>
                        <td style="padding: 6px 0;">{{ $webinar->topic ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0;">Speaker</td>
                        <td style="padding: 6px 0;">
                            {{ $webinar->speaker_name ?? 'N/A' }}
                            @if(!empty($webinar->speaker_designation))
                                ({{ $webinar->speaker_designation }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0;">Meeting Link</td>
                        <td style="padding: 6px 0;">
                            @if(!empty($webinar->meeting_link))
                                <a href="{{ $webinar->meeting_link }}" style="color: #2563eb; text-decoration: underline;">Join Webinar</a>
                            @else
                                Will be shared before the session.
                            @endif
                        </td>
                    </tr>
                </table>

                @if(!empty($webinar->description))
                    <p style="margin: 16px 0 0; font-size: 14px; color: #4b5563;">{{ $webinar->description }}</p>
                @endif

                <div style="margin-top: 20px; text-align: center;">
                    <a href="{{ $detailsUrl }}" style="display: inline-block; padding: 10px 18px; background: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold;">View Webinar Details</a>
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding: 16px 24px; background: #f9fafb; text-align: center; font-size: 12px; color: #6b7280;">
                This email confirms your registration. Keep it handy for webinar access.
            </td>
        </tr>
    </table>
</body>
</html>
