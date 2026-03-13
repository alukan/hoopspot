<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your email</title>
    <style>
        body { margin: 0; padding: 0; background: #111827; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        .wrapper { max-width: 520px; margin: 40px auto; background: #1f2937; border-radius: 16px; overflow: hidden; }
        .header { background: #111827; padding: 32px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .logo { font-size: 22px; font-weight: 800; color: #f97316; letter-spacing: -0.5px; }
        .body { padding: 36px 32px; }
        h1 { margin: 0 0 8px; font-size: 20px; font-weight: 700; color: #f9fafb; }
        p { margin: 0 0 24px; font-size: 15px; line-height: 1.6; color: #9ca3af; }
        .btn { display: inline-block; background: #f97316; color: #fff; text-decoration: none; font-weight: 700; font-size: 15px; padding: 14px 32px; border-radius: 10px; }
        .footer { padding: 20px 32px; border-top: 1px solid rgba(255,255,255,0.06); }
        .footer p { margin: 0; font-size: 12px; color: #4b5563; }
        .url { word-break: break-all; font-size: 12px; color: #6b7280; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">Hoop Spot</div>
        </div>
        <div class="body">
            <h1>Verify your email, {{ $user->name }}</h1>
            <p>Thanks for signing up! Click the button below to verify your email address. The link expires in 24 hours.</p>
            <a href="{{ $url }}" class="btn">Verify email</a>
            <p class="url">Or copy this link: {{ $url }}</p>
        </div>
        <div class="footer">
            <p>If you didn't create an account you can ignore this email.</p>
        </div>
    </div>
</body>
</html>
