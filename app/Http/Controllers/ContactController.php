<?php

namespace App\Http\Controllers;

use App\Mail\ContactAutoReply;
use App\Mail\ContactFormMail;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Save to database
        $contactMessage = ContactMessage::create($validated);

        // Send emails
        try {
            $adminEmail = Setting::get('site_email', config('mail.from.address'));

            // Notify admin
            Mail::to($adminEmail)->send(new ContactFormMail($contactMessage));

            // Auto-reply to sender
            Mail::to($contactMessage->email)->send(new ContactAutoReply($contactMessage));
        } catch (\Exception $e) {
            Log::error('Contact form email failed: ' . $e->getMessage(), [
                'contact_message_id' => $contactMessage->id,
            ]);
            // Don't fail the form submission just because email failed
        }

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
