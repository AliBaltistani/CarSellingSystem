<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarAttributeValue;
use App\Models\Category;
use App\Models\CarImage;
use App\Models\DropdownOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with(['category', 'user', 'images']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        // Filter by published
        if ($request->filled('published')) {
            $query->where('is_published', $request->published === 'yes');
        }

        $cars = $query->latest()->paginate(15);
        $categories = Category::orderBy('name')->get();

        return view('admin.cars.index', compact('cars', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $dropdownOptions = $this->getDropdownOptions();
        return view('admin.cars.create', compact('categories', 'dropdownOptions'));
    }

    public function store(Request $request)
    {
        try {
            // Use dynamic DB values to stay in sync with admin-managed dropdown options
            $validConditions = DropdownOption::byType(DropdownOption::TYPE_CONDITION)->pluck('value')->toArray();
            $validTransmissions = DropdownOption::byType(DropdownOption::TYPE_TRANSMISSION)->pluck('value')->toArray();
            $validFuelTypes = DropdownOption::byType(DropdownOption::TYPE_FUEL_TYPE)->pluck('value')->toArray();

            $validated = $request->validate([
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
                'whatsapp_number' => 'required|string|max:20',
                'phone_number' => 'nullable|string|max:20',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'address' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'status' => 'required|in:available,sold,pending,reserved',
                'is_featured' => 'boolean',
                'is_published' => 'boolean',
                'negotiable' => 'boolean',
                'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            $validated['user_id'] = auth()->id(); // Admin creates it, so it belongs to admin
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_published'] = $request->boolean('is_published');
            $validated['negotiable'] = $request->boolean('negotiable');
            
            if ($validated['is_published']) {
                $validated['published_at'] = now();
            }


            $car = Car::create($validated);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
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

            return redirect()->route('admin.cars.index')
                ->with('success', 'Car created successfully!');
        } catch (\Throwable $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'Failed to create car: ' . $e->getMessage());
        }
    }

    // ... show method ...

    public function show(Car $car)
    {
        $car->load(['category', 'images', 'user', 'attributeValues.attribute.group', 'attributeValues.attribute.options']);

        // Get related cars (reuse logic from public controller, but don't filter by published/available if needed, 
        // though usually related cars should be valid public cars)
        $relatedCars = Car::with(['images'])
            ->where('is_published', true)
            ->where('status', 'available')
            ->where('id', '!=', $car->id)
            ->where(function ($query) use ($car) {
                $query->where('category_id', $car->category_id)
                    ->orWhere('make', $car->make);
            })
            ->latest()
            ->take(4)
            ->get();

        // Pass empty SEO as it's an admin preview, or generate it if desired. 
        // The view expects $seo variable.
        $seo = []; 

        return view('cars.show', compact('car', 'relatedCars', 'seo'));
    }

    public function edit(Car $car)
    {
        $categories = Category::active()->orderBy('name')->get();
        $dropdownOptions = $this->getDropdownOptions();
        $car->load(['images', 'attributeValues']);
        return view('admin.cars.edit', compact('car', 'categories', 'dropdownOptions'));
    }

    /**
     * Get dropdown options for the form.
     */
    private function getDropdownOptions()
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

    public function update(Request $request, Car $car)
    {
        // Use dynamic DB values to stay in sync with admin-managed dropdown options
        $validConditions = DropdownOption::byType(DropdownOption::TYPE_CONDITION)->pluck('value')->toArray();
        $validTransmissions = DropdownOption::byType(DropdownOption::TYPE_TRANSMISSION)->pluck('value')->toArray();
        $validFuelTypes = DropdownOption::byType(DropdownOption::TYPE_FUEL_TYPE)->pluck('value')->toArray();

        $validated = $request->validate([
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
            'whatsapp_number' => 'required|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:available,sold,pending,reserved',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'negotiable' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');
        $validated['negotiable'] = $request->boolean('negotiable');

        // Handle sold status
        if ($validated['status'] === 'sold' && $car->status !== 'sold') {
            $validated['sold_at'] = now();
        }

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

        return redirect()->route('admin.cars.index')
            ->with('success', 'Car updated successfully!');
    }

    public function destroy(Car $car)
    {
        // Delete associated images from storage
        foreach ($car->images as $image) {
            Storage::disk('public')->delete($image->path);
            if ($image->thumbnail_path) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }
        }

        $car->delete();

        return redirect()->route('admin.cars.index')
            ->with('success', 'Car deleted successfully!');
    }

    public function toggleFeatured(Car $car)
    {
        $car->update(['is_featured' => !$car->is_featured]);
        return back()->with('success', 'Featured status updated!');
    }

    public function togglePublished(Car $car)
    {
        $data = ['is_published' => !$car->is_published];
        if (!$car->is_published && !$car->published_at) {
            $data['published_at'] = now();
        }
        $car->update($data);
        return back()->with('success', 'Published status updated!');
    }

    public function deleteImage(CarImage $image)
    {
        Storage::disk('public')->delete($image->path);
        if ($image->thumbnail_path) {
            Storage::disk('public')->delete($image->thumbnail_path);
        }
        $image->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Image deleted']);
        }
        return back()->with('success', 'Image deleted successfully!');
    }

    public function setPrimaryImage(CarImage $image)
    {
        // Unset all other primary images for this car
        CarImage::where('car_id', $image->car_id)->update(['is_primary' => false]);
        
        // Set this image as primary
        $image->update(['is_primary' => true]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Primary image updated']);
        }
        return back()->with('success', 'Primary image updated!');
    }
}
