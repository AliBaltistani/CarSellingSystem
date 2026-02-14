<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $replySubject }}</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 20px; }
        .card { background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.07); }
        .header { background: linear-gradient(135deg, #f59e0b, #ea580c); padding: 28px 32px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; }
        .body { padding: 32px; }
        .body p { color: #334155; font-size: 15px; line-height: 1.7; margin: 0 0 16px; }
        .message-box { background: #f8fafc; border-left: 4px solid #f59e0b; padding: 16px 20px; border-radius: 0 8px 8px 0; margin: 20px 0; }
        .message-box p { margin: 0; color: #334155; font-size: 15px; line-height: 1.7; white-space: pre-wrap; }
        .original { background: #f1f5f9; border-radius: 8px; padding: 16px 20px; margin-top: 24px; border: 1px solid #e2e8f0; }
        .original-label { font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }
        .original p { color: #64748b; font-size: 13px; line-height: 1.6; margin: 0; }
        .footer { text-align: center; padding: 20px 32px; background: #f8fafc; border-top: 1px solid #e2e8f0; }
        .footer p { color: #94a3b8; font-size: 13px; margin: 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>{{ $siteName }}</h1>
            </div>
            <div class="body">
                <p>Hi <strong>{{ $contactMessage->name }}</strong>,</p>

                <div class="message-box">
                    <p>{{ $replyMessage }}</p>
                </div>

                <div class="original">
                    <div class="original-label">Your Original Message</div>
                    <p><strong>Subject:</strong> {{ $contactMessage->subject }}</p>
                    <p style="margin-top: 8px;">{{ Str::limit($contactMessage->message, 500) }}</p>
                </div>

                <p style="margin-top: 24px;">Best regards,<br><strong>The {{ $siteName }} Team</strong></p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
