<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; margin: 0; padding: 20px;">
<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px;">
    <h2 style="color: #333333; text-align: center;">Welcome to {{ env('APP_NAME') }}, {{ $user->name }}!</h2>
    <p style="font-size: 16px; color: #555555; text-align: center;">
        Thank you for registering with us. To complete your registration, please confirm your email address by clicking the button below:
    </p>
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('registration-confirmation.render', [$user->token]) }}" style="display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Confirm Your Email
        </a>
    </div>
    <p style="font-size: 14px; color: #777777; text-align: center;">
        If you didn't register on our platform, you can safely ignore this email.
    </p>
    <p style="font-size: 14px; color: #777777; text-align: center;">
        Best regards,<br>{{ env('APP_NAME') }} Team
    </p>
</div>
</body>
</html>
