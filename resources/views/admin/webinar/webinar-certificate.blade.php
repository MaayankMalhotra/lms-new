<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Webinar Certificate</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333333;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden;">
        <tr>
            <td style="padding: 20px; text-align: center;">
                <img src="http://16.16.64.105/images/THINK%20CHAMP%20logo2.png" alt="Think Champ Logo" style="width: 160px; height: auto;">
            </td>
        </tr>

        <tr>
            <td style="padding: 10px 20px; text-align: center;">
                <h1 style="margin: 0; font-size: 28px; font-weight: bold; color: #0c3c7c;">
                    Certificate of Participation
                </h1>
            </td>
        </tr>

        <tr>
            <td style="padding: 20px;">
                <p style="font-size: 16px;">
                    Dear <strong>{{ $enrollment->name }}</strong>,
                </p>

                <p style="font-size: 16px;">
                    Thank you for attending the webinar: 
                    <strong>{{ $enrollment->webinar->title ?? 'Webinar' }}</strong>.
                </p>

                <p style="font-size: 16px;">
                    We are pleased to provide you with your certificate of participation.
                </p>

                <p style="font-size: 16px;">
                    Click the button below to view and download your certificate:
                </p>

                <p style="text-align: center; margin: 30px 0;">
                    <a href="{{ $certificateUrl }}" target="_blank" 
                       style="display: inline-block; padding: 12px 24px; background-color: #0c3c7c; color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 16px;">
                        View Certificate
                    </a>
                </p>

                <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

                <p style="font-size: 14px; text-align: center; color: #666;">
                    <strong>Certificate ID:</strong><br>
                    <span style="display: inline-block; margin-top: 5px; font-size: 16px; font-weight: bold; color: #0c3c7c;">
                        {{ $certificateId }}
                    </span>
                </p>

                <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

                <p style="font-size: 16px;">
                    We hope you enjoyed the session and look forward to seeing you at future events!
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding: 20px; text-align: center; background-color: #0c3c7c; color: #ffffff;">
                <p style="margin: 0; font-size: 14px;">&copy; {{ date('Y') }} Think Champ. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
