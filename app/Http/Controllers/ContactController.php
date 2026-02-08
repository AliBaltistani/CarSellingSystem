<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

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

        // Get admin email from settings
        $adminEmail = Setting::get('site_email', config('mail.from.address'));

        // For now, just store in session and show success message
        // In production, you would send an email here:
        // Mail::to($adminEmail)->send(new ContactFormMail($validated));

        // Log the contact submission (you could also store in database)
        \Log::info('Contact form submission', $validated);

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
