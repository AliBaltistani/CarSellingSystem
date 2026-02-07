<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    /**
     * Search for locations using OpenStreetMap Nominatim API.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $query = $request->input('q');
        $cacheKey = 'nominatim_search_' . md5($query);

        // Cache results for 1 hour to reduce API calls
        $results = Cache::remember($cacheKey, 3600, function () use ($query) {
            $response = Http::withHeaders([
                'User-Agent' => config('app.name') . '/1.0',
                'Accept-Language' => 'en',
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'addressdetails' => 1,
                'limit' => 5,
                'countrycodes' => 'pk,ae,sa,in', // Limit to relevant countries
                'accept-language' => 'en',
            ]);

            if ($response->successful()) {
                return collect($response->json())->map(function ($item) {
                    return [
                        'display_name' => $item['display_name'],
                        'name' => $item['name'] ?? $item['display_name'],
                        'city' => $item['address']['city'] ?? $item['address']['town'] ?? $item['address']['village'] ?? null,
                        'state' => $item['address']['state'] ?? null,
                        'country' => $item['address']['country'] ?? null,
                        'lat' => $item['lat'],
                        'lon' => $item['lon'],
                    ];
                })->toArray();
            }

            return [];
        });

        return response()->json($results);
    }

    /**
     * Combined search: DB first, then API fallback.
     */
    public function searchCombined(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $query = $request->input('q');
        
        // First search DB locations
        $dbLocations = Location::active()
            ->search($query)
            ->limit(5)
            ->get()
            ->map(function ($location) {
                return [
                    'id' => $location->id,
                    'city' => $location->city,
                    'state' => $location->state,
                    'country' => $location->country,
                    'display_name' => $location->formatted_name,
                    'lat' => $location->latitude,
                    'lon' => $location->longitude,
                    'source' => 'db',
                ];
            });

        // If less than 5 results, fetch from API
        if ($dbLocations->count() < 5) {
            $apiResults = $this->searchNominatim($query, 5 - $dbLocations->count());
            
            // Filter out API results that already exist in DB results
            $dbCities = $dbLocations->pluck('city')->map(fn($c) => strtolower($c))->toArray();
            
            $filteredApiResults = collect($apiResults)->filter(function ($item) use ($dbCities) {
                return !in_array(strtolower($item['city'] ?? ''), $dbCities);
            })->map(function ($item) {
                return array_merge($item, ['source' => 'api']);
            });

            $dbLocations = $dbLocations->merge($filteredApiResults);
        }

        return response()->json($dbLocations->values());
    }

    /**
     * Create a location from API data.
     */
    public function createFromApi(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'display_name' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
        ]);

        $location = Location::findOrCreateFromApi([
            'city' => $validated['city'],
            'state' => $validated['state'] ?? null,
            'country' => $validated['country'],
            'display_name' => $validated['display_name'] ?? null,
            'lat' => $validated['lat'] ?? null,
            'lon' => $validated['lon'] ?? null,
        ]);

        return response()->json([
            'id' => $location->id,
            'city' => $location->city,
            'state' => $location->state,
            'country' => $location->country,
            'display_name' => $location->formatted_name,
        ]);
    }

    /**
     * Helper to search Nominatim API.
     */
    protected function searchNominatim(string $query, int $limit = 5): array
    {
        $cacheKey = 'nominatim_search_' . md5($query . '_' . $limit);

        return Cache::remember($cacheKey, 3600, function () use ($query, $limit) {
            $response = Http::withHeaders([
                'User-Agent' => config('app.name') . '/1.0',
                'Accept-Language' => 'en',
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'addressdetails' => 1,
                'limit' => $limit,
                'accept-language' => 'en',
            ]);

            if ($response->successful()) {
                return collect($response->json())->map(function ($item) {
                    return [
                        'display_name' => $item['display_name'],
                        'city' => $item['address']['city'] ?? $item['address']['town'] ?? $item['address']['village'] ?? $item['name'] ?? null,
                        'state' => $item['address']['state'] ?? null,
                        'country' => $item['address']['country'] ?? null,
                        'lat' => $item['lat'],
                        'lon' => $item['lon'],
                    ];
                })->filter(function ($item) {
                    return !empty($item['city']);
                })->toArray();
            }

            return [];
        });
    }

    /**
     * Reverse geocode coordinates to get location name.
     */
    public function reverse(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180',
        ]);

        $lat = $request->input('lat');
        $lon = $request->input('lon');
        $cacheKey = 'nominatim_reverse_' . md5($lat . '_' . $lon);

        $result = Cache::remember($cacheKey, 3600, function () use ($lat, $lon) {
            $response = Http::withHeaders([
                'User-Agent' => config('app.name') . '/1.0',
                'Accept-Language' => 'en',
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $lat,
                'lon' => $lon,
                'format' => 'json',
                'addressdetails' => 1,
                'accept-language' => 'en',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'display_name' => $data['display_name'] ?? null,
                    'city' => $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? null,
                    'state' => $data['address']['state'] ?? null,
                    'country' => $data['address']['country'] ?? null,
                    'lat' => $lat,
                    'lon' => $lon,
                ];
            }

            return null;
        });

        if (!$result) {
            return response()->json(['error' => 'Location not found'], 404);
        }

        return response()->json($result);
    }
}

