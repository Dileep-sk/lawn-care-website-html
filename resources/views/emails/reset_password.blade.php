<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body style="margin:0; padding:0; background-color:#f8f9fb; font-family:Arial, sans-serif;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f8f9fb">
        <tr>
            <td align="center" style="padding:40px 15px;">
                <table width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" 
                       style="border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); overflow:hidden;">
                    
                    <tr>
                        <td align="center" bgcolor="#FF4757" style="padding:20px;">
                            <h1 style="margin:0; font-size:24px; font-weight:700; color:#ffffff;">
                                Password Reset Request
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px; color:#333333; font-size:16px; line-height:1.6;">
                            <p style="margin-top:0;">
                                Hello,
                            </p>
                            <p>
                                You are receiving this email because we received a password reset request for your account.
                            </p>

                            <p style="text-align:center; margin:30px 0;">
                                <a href="{{ $resetUrl }}" 
                                   style="background:linear-gradient(90deg,#FF7556 0%,#FF4757 100%); 
                                          color:#fff; padding:14px 30px; font-size:16px; 
                                          text-decoration:none; border-radius:6px; 
                                          font-weight:600; display:inline-block;">
                                    Reset Password
                                </a>
                            </p>

                            <p>
                                If you did not request a password reset, please ignore this email. 
                                This password reset link will expire in <strong>60 minutes</strong>.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td bgcolor="#f3f4f6" style="padding:20px; text-align:center; font-size:13px; color:#777;">
                            <p style="margin:0;">
                                &copy; {{ date('Y') }} Your Company. All rights reserved.
                            </p>
                            <p style="margin:5px 0 0;">
                                <a href="{{ config('app.url') }}" style="color:#FF4757; text-decoration:none;">Visit our website</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
