<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Internship Offer Letter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #4a90e2;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Internship Offer Letter</h1>
        </div>
        <div class="content">
            <p>Dear {{ $name }},</p>
            <p>We are pleased to offer you an internship opportunity with our organization. Congratulations on your selection!</p>
            <p>This internship is a recognition of your successful completion of the course and your enrollment in our program. We look forward to working with you and supporting your professional growth.</p>
            <p>Please contact us at <a href="mailto:support@yourcompany.com">support@yourcompany.com</a> to confirm your acceptance and discuss next steps.</p>
            <p>Best regards,</p>
            <p>Your Company Name</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Your Company Name. All rights reserved.</p>
        </div>
    </div>
</body>
</html>