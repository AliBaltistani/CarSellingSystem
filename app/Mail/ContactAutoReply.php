<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAutoReply extends Mailable
{
    use Queueable, SerializesModels;

    public string $siteName;

    public function __construct(
        public ContactMessage $contactMessage
    ) {
        $this->siteName = Setting::get('site_name') ?? config('app.name', 'Xenon Motors');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank you for contacting ' . $this->siteName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-auto-reply',
        );
    }
}
