<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Get car suggestions for search autocomplete.
     * Filters by: query text, make, year, condition, location
     */
    public function suggestions(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:100',
            'make' => 'nullable|string|max:50',
            'year' => 'nullable|integer|min:1900|max:2100',
            'condition' => 'nullable|string|in:new,used,certified',
            'city' => 'nullable|string|max:100',
        ]);

        $search = $request->input('q');
        $make = $request->input('make');
        $year = $request->input('year');
        $condition = $request->input('condition');
        $city = $request->input('city');

        $query = Car::with(['images', 'category'])
            ->published()
            ->available();

        // Apply text search if provided
        if ($search && strlen($search) >= 2) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('make', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Apply make filter
        if ($make) {
            $query->where('make', $make);
        }

        // Apply year filter
        if ($year) {
            $query->where('year', $year);
        }

        // Apply condition filter
        if ($condition) {
            $query->where('condition', $condition);
        }

        // Apply city/location filter - search across city, state, and country columns
        // This handles full location strings like "Abu Dhabi, United Arab Emirates"
        if ($city) {
            // Split the location by comma to handle "City, Country" format
            $locationParts = array_map('trim', explode(',', $city));
            
            $query->where(function ($q) use ($city, $locationParts) {
                // Try to match the full string against city
                $q->where('city', 'like', "%{$locationParts[0]}%");
                
                // Also match against country if provided in the location string
                if (count($locationParts) > 1) {
                    // Match city AND country for more accurate results
                    $q->orWhere(function ($subQ) use ($locationParts) {
                        $subQ->where('city', 'like', "%{$locationParts[0]}%")
                             ->where('country', 'like', "%{$locationParts[count($locationParts) - 1]}%");
                    });
                }
                
                // Also search in state and country columns for flexibility
                $q->orWhere('state', 'like', "%{$locationParts[0]}%")
                  ->orWhere('country', 'like', "%{$locationParts[0]}%");
            });
        }

        $cars = $query->limit(6)
            ->get()
            ->map(function ($car) {
                return [
                    'id' => $car->id,
                    'slug' => $car->slug,
                    'title' => $car->title,
                    'price' => $car->formatted_price,
                    'category' => $car->category?->name,
                    'city' => $car->city,
                    'image' => $car->main_image,
                    'url' => route('cars.show', $car),
                ];
            });

        return response()->json($cars);
    }
}
