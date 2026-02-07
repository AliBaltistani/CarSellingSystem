<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    /**
     * Display a listing of locations.
     */
    public function index(Request $request)
    {
        $query = Location::query();

        if ($search = $request->input('search')) {
            $query->search($search);
        }

        $locations = $query->orderBy('city')->paginate(20);

        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new location.
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store a newly created location.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'display_name' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Check for duplicate
        $existing = Location::where('city', $validated['city'])
            ->where('country', $validated['country'])
            ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors(['city' => 'This city already exists for the selected country.']);
        }

        Location::create($validated);

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Location created successfully.');
    }

    /**
     * Show the form for editing the specified location.
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified location.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'display_name' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Check for duplicate (excluding current)
        $existing = Location::where('city', $validated['city'])
            ->where('country', $validated['country'])
            ->where('id', '!=', $location->id)
            ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->withErrors(['city' => 'This city already exists for the selected country.']);
        }

        $location->update($validated);

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified location.
     */
    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Location deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Location $location)
    {
        $location->update(['is_active' => !$location->is_active]);

        return back()->with('success', 'Location status updated.');
    }

    /**
     * Search locations from Nominatim API.
     */
    public function searchApi(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $query = $request->input('q');

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'CarSellingSystem/1.0',
                'Accept-Language' => 'en',
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'addressdetails' => 1,
                'limit' => 10,
                'accept-language' => 'en',
            ]);

            if ($response->successful()) {
                $results = collect($response->json())->map(function ($item) {
                    $address = $item['address'] ?? [];
                    return [
                        'display_name' => $item['display_name'] ?? '',
                        'city' => $address['city'] ?? $address['town'] ?? $address['village'] ?? $address['municipality'] ?? '',
                        'state' => $address['state'] ?? $address['region'] ?? '',
                        'country' => $address['country'] ?? '',
                        'lat' => $item['lat'] ?? null,
                        'lon' => $item['lon'] ?? null,
                    ];
                })->filter(function ($item) {
                    return !empty($item['city']);
                })->values();

                return response()->json($results);
            }

            return response()->json([]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to search locations'], 500);
        }
    }
}
