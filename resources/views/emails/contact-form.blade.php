<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 20px; }
        .card { background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.07); }
        .header { background: linear-gradient(135deg, #f59e0b, #ea580c); padding: 28px 32px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; }
        .header p { color: rgba(255,255,255,0.85); margin: 6px 0 0; font-size: 14px; }
        .body { padding: 32px; }
        .field { margin-bottom: 20px; }
        .field-label { font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .field-value { font-size: 15px; color: #1e293b; line-height: 1.6; }
        .message-box { background: #f8fafc; border-left: 4px solid #f59e0b; padding: 16px 20px; border-radius: 0 8px 8px 0; margin-top: 8px; }
        .message-box p { margin: 0; color: #334155; font-size: 15px; line-height: 1.7; white-space: pre-wrap; }
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }
        .footer { text-align: center; padding: 20px 32px; background: #f8fafc; border-top: 1px solid #e2e8f0; }
        .footer p { color: #94a3b8; font-size: 13px; margin: 0; }
        .btn { display: inline-block; padding: 12px 28px; background: linear-gradient(135deg, #f59e0b, #ea580c); color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>📬 New Contact Message</h1>
                <p>Someone has reached out via the contact form</p>
            </div>
            <div class="body">
                <div class="field">
                    <div class="field-label">From</div>
                    <div class="field-value"><strong>{{ $contactMessage->name }}</strong></div>
                </div>

                <div class="field">
                    <div class="field-label">Email</div>
                    <div class="field-value"><a href="mailto:{{ $contactMessage->email }}" style="color: #f59e0b;">{{ $contactMessage->email }}</a></div>
                </div>

                @if($contactMessage->phone)
                <div class="field">
                    <div class="field-label">Phone</div>
                    <div class="field-value">{{ $contactMessage->phone }}</div>
                </div>
                @endif

                <div class="field">
                    <div class="field-label">Subject</div>
                    <div class="field-value">{{ $contactMessage->subject }}</div>
                </div>

                <hr class="divider">

                <div class="field">
                    <div class="field-label">Message</div>
                    <div class="message-box">
                        <p>{{ $contactMessage->message }}</p>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 24px;">
                    <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ urlencode($contactMessage->subject) }}" class="btn">
                        Reply to {{ $contactMessage->name }}
                    </a>
                </div>
            </div>
            <div class="footer">
                <p>Sent {{ $contactMessage->created_at->format('M d, Y \a\t h:i A') }} via Contact Form</p>
            </div>
        </div>
    </div>
</body>
</html>
