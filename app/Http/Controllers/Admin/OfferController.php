<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::ordered()->paginate(15);
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.form', ['offer' => new Offer()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:1024',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'badge' => 'nullable|string|max:100',
            'price_label' => 'nullable|string|max:100',
            'price_from' => 'nullable|numeric|min:0',
            'price_upgrade' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'features' => 'nullable|string',
            'cta_text' => 'nullable|string|max:50',
            'cta_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
            'expires_at' => 'nullable|date',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['order'] = $validated['order'] ?? 0;

        // Parse features from textarea (one per line)
        if (!empty($validated['features'])) {
            $validated['features'] = array_filter(array_map('trim', explode("\n", $validated['features'])));
        }

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('offers/icons', 'public');
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('offers/images', 'public');
        }

        Offer::create($validated);

        return redirect()->route('admin.offers.index')
            ->with('success', 'Offer created successfully.');
    }

    public function edit(Offer $offer)
    {
        return view('admin.offers.form', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:1024',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'badge' => 'nullable|string|max:100',
            'price_label' => 'nullable|string|max:100',
            'price_from' => 'nullable|numeric|min:0',
            'price_upgrade' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'features' => 'nullable|string',
            'cta_text' => 'nullable|string|max:50',
            'cta_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
            'expires_at' => 'nullable|date',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Parse features from textarea (one per line)
        if (!empty($validated['features'])) {
            $validated['features'] = array_filter(array_map('trim', explode("\n", $validated['features'])));
        } else {
            $validated['features'] = null;
        }

        if ($request->hasFile('icon')) {
            if ($offer->icon) {
                Storage::disk('public')->delete($offer->icon);
            }
            $validated['icon'] = $request->file('icon')->store('offers/icons', 'public');
        }

        if ($request->hasFile('image')) {
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }
            $validated['image'] = $request->file('image')->store('offers/images', 'public');
        }

        $offer->update($validated);

        return redirect()->route('admin.offers.index')
            ->with('success', 'Offer updated successfully.');
    }

    public function destroy(Offer $offer)
    {
        if ($offer->icon) {
            Storage::disk('public')->delete($offer->icon);
        }
        if ($offer->image) {
            Storage::disk('public')->delete($offer->image);
        }
        
        $offer->delete();

        return redirect()->route('admin.offers.index')
            ->with('success', 'Offer deleted successfully.');
    }

    public function toggleActive(Offer $offer)
    {
        $offer->update(['is_active' => !$offer->is_active]);
        
        return back()->with('success', 
            'Offer ' . ($offer->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }
}
