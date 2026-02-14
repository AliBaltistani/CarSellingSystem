<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Car;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;

class CarListingController extends Controller
{
    /**
     * Return paginated car listings as JSON.
     * Supports all filters, geolocation sorting, and section filtering.
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:48',
            'section' => 'nullable|string|in:featured,latest,all',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|integer|min:1|max:1000',
            'search' => 'nullable|string|max:200',
            'make' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'min_year' => 'nullable|integer|min:1900',
            'max_year' => 'nullable|integer|max:2100',
            'condition' => 'nullable|string|max:50',
            'transmission' => 'nullable|string|max:50',
            'fuel_type' => 'nullable|string|max:50',
            'body_type' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:200',
        ]);

        $perPage = (int) $request->input('per_page', Setting::get('cars_per_page', 12));
        $section = $request->input('section', 'all');

        $query = Car::with(['category', 'images', 'attributeValues.attribute'])
            ->published()
            ->available();

        // Section-specific filters
        if ($section === 'featured') {
            $query->featured();
        }

        // Apply standard filters
        $query->filter($request->only([
            'make', 'model', 'min_price', 'max_price',
            'min_year', 'max_year', 'condition', 'transmission',
            'fuel_type', 'body_type', 'category', 'search', 'city'
        ]));

        // Apply dynamic attribute filters
        $attributeFilters = $request->input('attr', []);
        if (!empty($attributeFilters)) {
            $query->filterByAttributes($attributeFilters);
        }

        // Location-based sorting
        $hasLocation = $request->filled('latitude') && $request->filled('longitude');
        if ($hasLocation) {
            $radius = (int) $request->input('radius', 500);
            $query->nearby(
                (float) $request->latitude,
                (float) $request->longitude,
                $radius
            );
        } else {
            $query->latest();
        }

        $cars = $query->paginate($perPage);

        // Check if user is authenticated for favorites
        $user = auth()->user();

        $data = $cars->map(function ($car) use ($user, $hasLocation) {
            $item = [
                'id' => $car->id,
                'slug' => $car->slug,
                'title' => $car->title,
                'price' => $car->formatted_price,
                'raw_price' => $car->price,
                'year' => $car->year,
                'make' => $car->make,
                'model' => $car->model,
                'mileage' => $car->mileage ? number_format($car->mileage) : null,
                'transmission' => $car->transmission,
                'fuel_type' => $car->fuel_type,
                'condition' => ucfirst($car->condition),
                'city' => $car->city ?? 'UAE',
                'image' => $car->main_image,
                'url' => route('cars.show', $car),
                'whatsapp_link' => $car->whatsapp_link,
                'is_featured' => (bool) $car->is_featured,
                'is_favorited' => $user ? $car->isFavoritedBy($user) : false,
                'category' => $car->category?->name,
                'negotiable' => (bool) $car->negotiable,
            ];

            // Include dynamic attributes marked for card display
            $cardAttrs = $car->attributeValues
                ->filter(fn($av) => $av->value !== null && $av->value !== '' && $av->attribute && $av->attribute->show_in_card && $av->attribute->is_active)
                ->map(fn($av) => [
                    'name' => $av->attribute->name,
                    'value' => $av->attribute->formatValue($av->typed_value),
                    'icon' => $av->attribute->icon,
                    'suffix' => $av->attribute->suffix,
                    'prefix' => $av->attribute->prefix,
                ])
                ->values()
                ->toArray();
            $item['card_attributes'] = $cardAttrs;

            // Include distance if location search is active
            if ($hasLocation && isset($car->distance)) {
                $item['distance_km'] = round($car->distance, 1);
            }

            return $item;
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $cars->currentPage(),
                'last_page' => $cars->lastPage(),
                'per_page' => $cars->perPage(),
                'total' => $cars->total(),
                'has_more' => $cars->hasMorePages(),
            ],
        ]);
    }
}
