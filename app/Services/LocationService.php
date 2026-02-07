<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LocationService
{
    /**
     * Reverse geocode coordinates to get address details
     */
    public function reverseGeocode(float $latitude, float $longitude): ?array
    {
        $cacheKey = "geocode_{$latitude}_{$longitude}";
        
        return Cache::remember($cacheKey, 86400, function () use ($latitude, $longitude) {
            // Using OpenStreetMap Nominatim (free)
            $response = Http::timeout(10)->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $latitude,
                'lon' => $longitude,
                'format' => 'json',
                'addressdetails' => 1,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'city' => $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? null,
                    'state' => $data['address']['state'] ?? null,
                    'country' => $data['address']['country'] ?? null,
                    'country_code' => $data['address']['country_code'] ?? null,
                    'address' => $data['display_name'] ?? null,
                ];
            }
            
            return null;
        });
    }

    /**
     * Calculate distance between two points using Haversine formula
     */
    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2, string $unit = 'km'): float
    {
        $earthRadius = $unit === 'miles' ? 3959 : 6371;
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return round($earthRadius * $c, 2);
    }

    /**
     * Get cars within a radius of given coordinates
     */
    public function getCarsNearLocation(float $latitude, float $longitude, int $radiusKm = 50)
    {
        return DB::table('cars')
            ->select('*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                sin(radians(latitude)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->having('distance', '<=', $radiusKm)
            ->where('is_published', true)
            ->where('status', 'available')
            ->orderBy('distance')
            ->get();
    }

    /**
     * Get popular cities with car counts
     */
    public function getPopularCities(int $limit = 10)
    {
        return Cache::remember('popular_cities', 3600, function () use ($limit) {
            return DB::table('cars')
                ->select('city', DB::raw('COUNT(*) as car_count'))
                ->where('is_published', true)
                ->where('status', 'available')
                ->whereNotNull('city')
                ->groupBy('city')
                ->orderByDesc('car_count')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get available countries with car counts
     */
    public function getAvailableCountries()
    {
        return Cache::remember('available_countries', 3600, function () {
            return DB::table('cars')
                ->select('country', DB::raw('COUNT(*) as car_count'))
                ->where('is_published', true)
                ->where('status', 'available')
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderByDesc('car_count')
                ->get();
        });
    }
}
