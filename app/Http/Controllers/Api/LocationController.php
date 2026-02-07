<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'addressdetails' => 1,
                'limit' => 5,
                'countrycodes' => 'pk,ae,sa,in', // Limit to relevant countries
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
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $lat,
                'lon' => $lon,
                'format' => 'json',
                'addressdetails' => 1,
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
