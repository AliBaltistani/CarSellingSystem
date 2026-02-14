<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $siteName;

    public function __construct(
        public ContactMessage $contactMessage,
        public string $replyMessage,
        public string $replySubject,
    ) {
        $this->siteName = Setting::get('site_name') ?? config('app.name', 'Xenon Motors');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->replySubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-reply',
        );
    }
}
