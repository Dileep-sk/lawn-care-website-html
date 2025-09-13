<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <p>You are receiving this email because we received a password reset request for your account.</p>

    <p>
        <a href="{{ $resetUrl }}" style="background-color:#FF4757; color:#fff; padding:10px 20px; text-decoration:none; border-radius:5px;">
            Reset Password
        </a>
    </p>

    <p>If you did not request a password reset, no further action is required.</p>
</body>
</html>
