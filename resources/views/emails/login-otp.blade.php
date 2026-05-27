<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 30px;">

    <div style="max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 12px;">

        <h2 style="margin-top: 0; color: #0f172a;">
            Login Verification
        </h2>

        <p style="color: #475569;">
            Use the OTP below to complete your login:
        </p>

        <div style="margin: 30px 0; text-align: center;">

            <div style="
                display: inline-block;
                background: #0f172a;
                color: white;
                padding: 14px 24px;
                border-radius: 10px;
                font-size: 28px;
                letter-spacing: 6px;
                font-weight: bold;
            ">
                {{ $otp }}
            </div>

        </div>

        <p style="color: #64748b; font-size: 14px;">
            This OTP will expire in 5 minutes.
        </p>

        <p style="color: #64748b; font-size: 14px;">
            If you did not attempt to login, please ignore this email.
        </p>

    </div>

</body>
</html>
