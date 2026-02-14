<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Car;
use App\Models\CarAttributeValue;
use App\Models\Category;
use App\Models\CarImage;
use App\Models\Setting;
use App\Models\DropdownOption;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class CarController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    public function index(Request $request)
    {
        $query = Car::with(['category', 'images'])
            ->published()
            ->available();

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

        // Location-based filtering
        if ($request->filled(['latitude', 'longitude'])) {
            $radius = $request->input('radius', 50);
            $query->nearby(
                (float) $request->latitude,
                (float) $request->longitude,
                (int) $radius
            );
        } else {
            $query->latest();
        }

        $cars = $query->paginate((int) Setting::get('cars_per_page', 12));
        $categories = Category::active()->orderBy('order')->get();

        // Get filter options from admin-managed dropdown options
        $makes = DropdownOption::byType(DropdownOption::TYPE_MAKE);
        $conditions = DropdownOption::byType(DropdownOption::TYPE_CONDITION);
        $transmissions = DropdownOption::byType(DropdownOption::TYPE_TRANSMISSION);
        $fuelTypes = DropdownOption::byType(DropdownOption::TYPE_FUEL_TYPE);
        $bodyTypes = DropdownOption::byType(DropdownOption::TYPE_BODY_TYPE);

        // Get filterable dynamic attributes with options
        $attributesQuery = Attribute::active()
            ->filterable()
            ->with(['options', 'group'])
            ->ordered();

        // Filter attributes by category if selected
        if ($request->has('category')) {
            $categorySlug = $request->input('category');
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                // If category found, filter attributes by this category
                $attributesQuery->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                });
            }
        }

        $filterableAttributes = $attributesQuery->get()
            ->groupBy(fn($attr) => $attr->group?->name ?? 'Other');

        $seo = $this->seoService->generateListingMeta($request->all());

        return view('cars.index', compact(
            'cars', 'categories', 'makes', 'conditions', 'transmissions',
            'fuelTypes', 'bodyTypes', 'filterableAttributes', 'seo'
        ));
    }

    public function show(Car $car)
    {
        if (!$car->is_published && (!auth()->check() || auth()->id() !== $car->user_id)) {
            abort(404);
        }

        $car->load(['category', 'images', 'user', 'attributeValues.attribute.group', 'attributeValues.attribute.options']);
        
        // Increment views
        $car->incrementViews();

        // Get related cars
        $relatedCars = Car::with(['images', 'attributeValues.attribute'])
            ->published()
            ->available()
            ->where('id', '!=', $car->id)
            ->where(function ($query) use ($car) {
                $query->where('category_id', $car->category_id)
                    ->orWhere('make', $car->make);
            })
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $seo = $this->seoService->generateCarMeta($car);
        $jsonLd = $this->seoService->generateStructuredData($car);

        return view('cars.show', compact('car', 'relatedCars', 'seo', 'jsonLd'));
    }

    public function byCategory(Category $category, Request $request)
    {
        $query = Car::with(['category', 'images'])
            ->published()
            ->available()
            ->where('category_id', $category->id);

        // Apply filters
        $query->filter($request->except('category'));
        
        // Apply dynamic attribute filters
        $attributeFilters = $request->input('attr', []);
        if (!empty($attributeFilters)) {
            $query->filterByAttributes($attributeFilters);
        }
        
        $cars = $query->latest()->paginate((int) Setting::get('cars_per_page', 12));
        $categories = Category::active()->orderBy('order')->get();

        // Get filter options from admin-managed dropdown options
        $makes = DropdownOption::byType(DropdownOption::TYPE_MAKE);
        $conditions = DropdownOption::byType(DropdownOption::TYPE_CONDITION);
        $transmissions = DropdownOption::byType(DropdownOption::TYPE_TRANSMISSION);
        $fuelTypes = DropdownOption::byType(DropdownOption::TYPE_FUEL_TYPE);
        $bodyTypes = DropdownOption::byType(DropdownOption::TYPE_BODY_TYPE);

        // Get filterable dynamic attributes with options
        $filterableAttributes = Attribute::active()
            ->filterable()
            ->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->with(['options', 'group'])
            ->ordered()
            ->get()
            ->groupBy(fn($attr) => $attr->group?->name ?? 'Other');

        $seo = $this->seoService->generateListingMeta(['category' => $category->name]);

        return view('cars.index', compact(
            'cars', 'categories', 'category', 'makes', 'conditions', 'transmissions',
            'fuelTypes', 'bodyTypes', 'filterableAttributes', 'seo'
        ));
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $query = Car::with(['category', 'images'])
            ->published()
            ->available()
            ->where(function ($q) use ($request) {
                $search = $request->input('q');
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });

        $cars = $query->latest()->paginate(12);

        return view('cars.index', [
            'cars' => $cars,
            'searchQuery' => $request->input('q'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | User's Own Listings
    |--------------------------------------------------------------------------
    */

    public function myListings()
    {
        $cars = Car::with(['category', 'images'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('cars.my-listings', compact('cars'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $dropdownOptions = $this->getDropdownOptions();
        
        return view('cars.create', compact('categories', 'dropdownOptions'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $this->validateCarRequest($request);
            $validated['user_id'] = auth()->id();
            $validated['is_published'] = $request->boolean('is_published', true);
            $validated['published_at'] = $validated['is_published'] ? now() : null;

            $car = Car::create($validated);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    if (!$image->isValid()) {
                        throw new \Exception("Image {$index} failed to upload: " . $image->getErrorMessage());
                    }
                    
                    $path = $image->store('cars/' . $car->id, 'public');
                    CarImage::create([
                        'car_id' => $car->id,
                        'path' => $path,
                        'order' => $index,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            // Save dynamic attribute values
            if ($request->has('attributes')) {
                CarAttributeValue::saveForCar($car, $request->input('attributes', []));
            }

            DB::commit();

            return redirect()->route('cars.my-listings')
                ->with('success', 'Your car listing has been created!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Car creation failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create car listing: ' . $e->getMessage()]);
        }
    }

    public function edit(Car $car)
    {
        $this->authorizeCarOwner($car);

        $categories = Category::active()->orderBy('name')->get();
        $dropdownOptions = $this->getDropdownOptions();
        $car->load(['images', 'attributeValues']);

        return view('cars.edit', compact('car', 'categories', 'dropdownOptions'));
    }

    public function update(Request $request, Car $car)
    {
        $this->authorizeCarOwner($car);

        $validated = $this->validateCarRequest($request, $car);
        $car->update($validated);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $currentMaxOrder = $car->images()->max('order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('cars/' . $car->id, 'public');
                CarImage::create([
                    'car_id' => $car->id,
                    'path' => $path,
                    'order' => $currentMaxOrder + $index + 1,
                    'is_primary' => false,
                ]);
            }
        }

        // Save dynamic attribute values
        if ($request->has('attributes')) {
            CarAttributeValue::saveForCar($car, $request->input('attributes', []));
        }

        return redirect()->route('cars.my-listings')
            ->with('success', 'Your car listing has been updated!');
    }

    public function destroy(Car $car)
    {
        $this->authorizeCarOwner($car);

        // Delete images from storage
        foreach ($car->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $car->delete();

        return redirect()->route('cars.my-listings')
            ->with('success', 'Your car listing has been deleted!');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    protected function authorizeCarOwner(Car $car): void
    {
        if ($car->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Get all dropdown options for car forms
     */
    protected function getDropdownOptions(): array
    {
        return [
            'makes' => DropdownOption::byType(DropdownOption::TYPE_MAKE),
            'conditions' => DropdownOption::byType(DropdownOption::TYPE_CONDITION),
            'transmissions' => DropdownOption::byType(DropdownOption::TYPE_TRANSMISSION),
            'fuel_types' => DropdownOption::byType(DropdownOption::TYPE_FUEL_TYPE),
            'body_types' => DropdownOption::byType(DropdownOption::TYPE_BODY_TYPE),
            'drivetrains' => DropdownOption::byType(DropdownOption::TYPE_DRIVETRAIN),
            'exterior_colors' => DropdownOption::byType(DropdownOption::TYPE_EXTERIOR_COLOR),
            'interior_colors' => DropdownOption::byType(DropdownOption::TYPE_INTERIOR_COLOR),
            'doors' => DropdownOption::byType(DropdownOption::TYPE_DOORS),
            'seats' => DropdownOption::byType(DropdownOption::TYPE_SEATS),
            'cylinders' => DropdownOption::byType(DropdownOption::TYPE_CYLINDERS),
        ];
    }

    protected function validateCarRequest(Request $request, ?Car $car = null): array
    {
        // Get valid values from database
        $validConditions = DropdownOption::byType(DropdownOption::TYPE_CONDITION)->pluck('value')->toArray();
        $validTransmissions = DropdownOption::byType(DropdownOption::TYPE_TRANSMISSION)->pluck('value')->toArray();
        $validFuelTypes = DropdownOption::byType(DropdownOption::TYPE_FUEL_TYPE)->pluck('value')->toArray();

        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'price' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:' . implode(',', $validConditions),
            'transmission' => 'required|in:' . implode(',', $validTransmissions),
            'fuel_type' => 'required|in:' . implode(',', $validFuelTypes),
            'mileage' => 'nullable|integer|min:0',
            'body_type' => 'nullable|string|max:50',
            'exterior_color' => 'nullable|string|max:50',
            'interior_color' => 'nullable|string|max:50',
            'doors' => 'nullable|integer|min:2|max:6',
            'seats' => 'nullable|integer|min:1|max:12',
            'negotiable' => 'sometimes|boolean',
            'status' => 'nullable|in:available,sold,pending,reserved',
            'whatsapp_number' => 'required|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'images' => 'array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
    }
}
