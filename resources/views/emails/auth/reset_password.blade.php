<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; margin: 0; padding: 20px;">
<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px;">
    <h2 style="color: #333333; text-align: center;">Password Reset Request</h2>
    <p style="font-size: 16px; color: #555555; text-align: center;">
        Hi {{ $user->name }}, we received a request to reset your password for your {{ env('APP_NAME') }} account.
    </p>
    <p style="font-size: 16px; color: #555555; text-align: center;">
        If you made this request, please reset your password by clicking the button below:
    </p>
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('reset-password.render', [$user->password_reset_token]) }}" style="display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Reset Your Password
        </a>
    </div>
    <p style="font-size: 14px; color: #777777; text-align: center;">
        If you did not request a password reset, you can safely ignore this email. Your password will not be changed.
    </p>
    <p style="font-size: 14px; color: #777777; text-align: center;">
        Best regards,<br>{{ env('APP_NAME') }} Team
    </p>
</div>
</body>
</html>
