<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Us</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 20px; }
        .card { background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.07); }
        .header { background: linear-gradient(135deg, #f59e0b, #ea580c); padding: 28px 32px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; }
        .body { padding: 32px; }
        .body p { color: #334155; font-size: 15px; line-height: 1.7; margin: 0 0 16px; }
        .summary { background: #f8fafc; border-radius: 8px; padding: 20px 24px; margin: 24px 0; border: 1px solid #e2e8f0; }
        .summary-item { margin-bottom: 12px; }
        .summary-item:last-child { margin-bottom: 0; }
        .summary-label { font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        .summary-value { font-size: 14px; color: #1e293b; margin-top: 2px; }
        .footer { text-align: center; padding: 20px 32px; background: #f8fafc; border-top: 1px solid #e2e8f0; }
        .footer p { color: #94a3b8; font-size: 13px; margin: 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>✅ Message Received!</h1>
            </div>
            <div class="body">
                <p>Hi <strong>{{ $contactMessage->name }}</strong>,</p>

                <p>Thank you for reaching out to <strong>{{ $siteName }}</strong>! We've received your message and our team will get back to you as soon as possible.</p>

                <p>Here's a copy of what you sent us:</p>

                <div class="summary">
                    <div class="summary-item">
                        <div class="summary-label">Subject</div>
                        <div class="summary-value">{{ $contactMessage->subject }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Message</div>
                        <div class="summary-value">{{ Str::limit($contactMessage->message, 300) }}</div>
                    </div>
                </div>

                <p>We typically respond within 24–48 hours during business days. If your matter is urgent, please don't hesitate to call us directly.</p>

                <p>Best regards,<br><strong>The {{ $siteName }} Team</strong></p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
