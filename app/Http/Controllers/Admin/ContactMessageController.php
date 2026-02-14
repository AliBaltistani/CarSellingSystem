<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15)->appends($request->query());

        $counts = [
            'all' => ContactMessage::count(),
            'new' => ContactMessage::new()->count(),
            'read' => ContactMessage::read()->count(),
            'replied' => ContactMessage::replied()->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'counts'));
    }

    public function show(ContactMessage $contactMessage)
    {
        // Auto-mark as read
        if ($contactMessage->status === ContactMessage::STATUS_NEW) {
            $contactMessage->update(['status' => ContactMessage::STATUS_READ]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'reply_message' => 'required|string|max:10000',
        ]);

        try {
            Mail::to($contactMessage->email)->send(new ContactReplyMail(
                $contactMessage,
                $request->reply_message,
                $request->subject,
            ));

            $contactMessage->update(['status' => ContactMessage::STATUS_REPLIED]);

            return back()->with('success', 'Reply sent successfully to ' . $contactMessage->email);
        } catch (\Exception $e) {
            Log::error('Contact reply email failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send reply: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'status' => 'required|in:new,read,replied',
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $contactMessage->update($request->only('status', 'admin_notes'));

        return back()->with('success', 'Message status updated successfully.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Contact message deleted successfully.');
    }
}
