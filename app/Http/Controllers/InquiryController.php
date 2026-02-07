<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function store(Request $request, Car $car)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|min:10|max:1000',
        ]);

        $validated['car_id'] = $car->id;
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'new';

        Inquiry::create($validated);

        // Increment inquiries count on car
        $car->increment('inquiries_count');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your inquiry has been sent! The seller will contact you soon.',
            ]);
        }

        return back()->with('success', 'Your inquiry has been sent! The seller will contact you soon.');
    }

    public function myInquiries()
    {
        // Get inquiries on user's cars
        $receivedInquiries = Inquiry::with(['car.images'])
            ->whereHas('car', fn($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->paginate(10);

        // Get user's sent inquiries
        $sentInquiries = Inquiry::with(['car.images'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('inquiries.my-inquiries', compact('receivedInquiries', 'sentInquiries'));
    }
}
