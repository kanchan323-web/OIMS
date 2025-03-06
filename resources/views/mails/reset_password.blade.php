<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        h2 {
            color: #333;
        }
        p {
            color: #555;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            color: #ffffff;
            background: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Password Reset Request</h2>
        <p>Hello {{ $user->name }},</p>
        <p>We received a request to reset your password. Click the button below to reset it:</p>
        <p>
            <a href="{{ url('/reset-password/' . $user->id . '/' . $token) }}" class="btn">Reset Password</a>
        </p>
        <p>If you didn't request this, you can ignore this email.</p>
        <p class="footer">This is an automated email. Please do not reply.</p>
    </div>

</body>
</html>
