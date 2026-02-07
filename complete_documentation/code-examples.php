# Laravel Code Examples - Car Selling Web App
## Essential Code Snippets for Implementation

---

## 1. Car Model with Location Scopes

```php
<?php
// app/Models/Car.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Car extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasSlug, InteractsWithMedia;
    
    protected $fillable = [
        'title', 'slug', 'description', 'price', 'year', 'make', 'model',
        'condition', 'mileage', 'transmission', 'fuel_type', 'body_type',
        'color', 'latitude', 'longitude', 'city', 'state', 'country',
        'whatsapp_number', 'status', 'is_featured', 'is_published',
        'meta_title', 'meta_description', 'category_id', 'user_id'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'year' => 'integer',
        'mileage' => 'integer',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'featured_until' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];
    
    protected $appends = ['formatted_price', 'main_image'];
    
    // Slug configuration
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
    
    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }
    
    // Media collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(300)
                    ->height(200)
                    ->sharpen(10);
                    
                $this->addMediaConversion('large')
                    ->width(1200)
                    ->height(800)
                    ->sharpen(10);
            });
    }
    
    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'AED ' . number_format($this->price, 2);
    }
    
    public function getMainImageAttribute()
    {
        return $this->getFirstMediaUrl('images', 'large') 
            ?: asset('images/car-placeholder.jpg');
    }
    
    public function getWhatsappLinkAttribute()
    {
        $message = urlencode("Hi, I'm interested in {$this->title}");
        return "https://wa.me/{$this->whatsapp_number}?text={$message}";
    }
    
    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
    
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
    
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
            ->where(function($q) {
                $q->whereNull('featured_until')
                  ->orWhere('featured_until', '>', now());
            });
    }
    
    public function scopeNearby($query, $latitude, $longitude, $radiusKm = 50)
    {
        return $query->selectRaw("
            *,
            (6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * sin(radians(latitude))
            )) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<=', $radiusKm)
        ->orderBy('distance');
    }
    
    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['make'] ?? null, function($q, $make) {
                $q->where('make', $make);
            })
            ->when($filters['model'] ?? null, function($q, $model) {
                $q->where('model', $model);
            })
            ->when($filters['min_price'] ?? null, function($q, $minPrice) {
                $q->where('price', '>=', $minPrice);
            })
            ->when($filters['max_price'] ?? null, function($q, $maxPrice) {
                $q->where('price', '<=', $maxPrice);
            })
            ->when($filters['min_year'] ?? null, function($q, $minYear) {
                $q->where('year', '>=', $minYear);
            })
            ->when($filters['max_year'] ?? null, function($q, $maxYear) {
                $q->where('year', '<=', $maxYear);
            })
            ->when($filters['condition'] ?? null, function($q, $condition) {
                $q->where('condition', $condition);
            })
            ->when($filters['transmission'] ?? null, function($q, $transmission) {
                $q->where('transmission', $transmission);
            })
            ->when($filters['fuel_type'] ?? null, function($q, $fuelType) {
                $q->where('fuel_type', $fuelType);
            })
            ->when($filters['body_type'] ?? null, function($q, $bodyType) {
                $q->where('body_type', $bodyType);
            })
            ->when($filters['category'] ?? null, function($q, $category) {
                $q->whereHas('category', function($sq) use ($category) {
                    $sq->where('slug', $category);
                });
            })
            ->when($filters['search'] ?? null, function($q, $search) {
                $q->where(function($sq) use ($search) {
                    $sq->where('title', 'like', "%{$search}%")
                       ->orWhere('description', 'like', "%{$search}%")
                       ->orWhere('make', 'like', "%{$search}%")
                       ->orWhere('model', 'like', "%{$search}%");
                });
            });
    }
    
    // Methods
    public function incrementViews()
    {
        $this->increment('views_count');
        
        // Log view
        $this->views()->create([
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
    
    public function isFavoritedBy($user)
    {
        if (!$user) return false;
        return $this->favorites()->where('user_id', $user->id)->exists();
    }
}
```

---

## 2. Car Controller with Location Filter

```php
<?php
// app/Http/Controllers/CarController.php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with(['category', 'media'])
            ->published()
            ->available();
        
        // Apply filters
        $query->filter($request->all());
        
        // Location-based filtering
        if ($request->filled(['lat', 'lng'])) {
            $radius = $request->input('radius', 50);
            $query->nearby(
                $request->lat,
                $request->lng,
                $radius
            );
        }
        
        // Sorting
        $sort = $request->input('sort', 'date_desc');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'distance':
                // Already ordered by distance in nearby scope
                break;
            default:
                $query->latest();
        }
        
        $cars = $query->paginate($request->input('per_page', 20))
            ->withQueryString();
        
        $categories = Category::withCount('cars')->get();
        $makes = Car::published()->distinct()->pluck('make');
        
        return view('cars.index', compact('cars', 'categories', 'makes'));
    }
    
    public function show($slug)
    {
        $car = Car::with(['category', 'media', 'user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
        
        // Increment view count
        $car->incrementViews();
        
        // Get related cars (same category or make)
        $relatedCars = Car::published()
            ->available()
            ->where('id', '!=', $car->id)
            ->where(function($query) use ($car) {
                $query->where('category_id', $car->category_id)
                      ->orWhere('make', $car->make);
            })
            ->limit(4)
            ->get();
        
        return view('cars.show', compact('car', 'relatedCars'));
    }
    
    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:1|max:200',
        ]);
        
        $cars = Car::published()
            ->available()
            ->nearby(
                $request->lat,
                $request->lng,
                $request->input('radius', 50)
            )
            ->with('media')
            ->limit(20)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $cars,
            'count' => $cars->count(),
        ]);
    }
}
```

---

## 3. Location Service

```php
<?php
// app/Services/LocationService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LocationService
{
    /**
     * Reverse geocode coordinates to get address
     */
    public function reverseGeocode($latitude, $longitude)
    {
        $cacheKey = "geocode_{$latitude}_{$longitude}";
        
        return Cache::remember($cacheKey, 86400, function() use ($latitude, $longitude) {
            // Using OpenStreetMap Nominatim (free)
            $response = Http::get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $latitude,
                'lon' => $longitude,
                'format' => 'json',
                'addressdetails' => 1,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'city' => $data['address']['city'] ?? $data['address']['town'] ?? null,
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
     * Calculate distance between two points (Haversine formula)
     */
    public function calculateDistance($lat1, $lng1, $lat2, $lng2, $unit = 'km')
    {
        $earthRadius = $unit === 'miles' ? 3959 : 6371;
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
    
    /**
     * Get popular cities with car counts
     */
    public function getPopularCities()
    {
        return Cache::remember('popular_cities', 3600, function() {
            return \DB::table('cars')
                ->select('city', \DB::raw('COUNT(*) as car_count'))
                ->where('is_published', true)
                ->where('status', 'available')
                ->whereNotNull('city')
                ->groupBy('city')
                ->orderByDesc('car_count')
                ->limit(10)
                ->get();
        });
    }
}
```

---

## 4. Alpine.js Location Detector Component

```javascript
// resources/js/components/locationDetector.js

export default function locationDetector() {
    return {
        loading: false,
        detected: false,
        location: null,
        error: null,
        
        init() {
            // Check if location is already stored
            const stored = localStorage.getItem('userLocation');
            if (stored) {
                this.location = JSON.parse(stored);
                this.detected = true;
            }
        },
        
        async detectLocation() {
            this.loading = true;
            this.error = null;
            
            if (!navigator.geolocation) {
                this.error = 'Geolocation is not supported by your browser';
                this.loading = false;
                return;
            }
            
            try {
                const position = await this.getPosition();
                
                const locationData = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy,
                    timestamp: Date.now()
                };
                
                // Get city/country details
                const details = await this.reverseGeocode(
                    locationData.latitude,
                    locationData.longitude
                );
                
                this.location = { ...locationData, ...details };
                this.detected = true;
                
                // Store in localStorage
                localStorage.setItem('userLocation', JSON.stringify(this.location));
                
                // Send to backend
                await this.saveToBackend(this.location);
                
                // Trigger event for other components
                window.dispatchEvent(new CustomEvent('location-detected', {
                    detail: this.location
                }));
                
                // Reload page with location filters
                this.reloadWithLocation();
                
            } catch (error) {
                this.error = error.message;
                console.error('Location detection error:', error);
            } finally {
                this.loading = false;
            }
        },
        
        getPosition() {
            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(
                    resolve,
                    (error) => {
                        let message = 'Unable to get your location';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                message = 'Location permission denied';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                message = 'Location information unavailable';
                                break;
                            case error.TIMEOUT:
                                message = 'Location request timed out';
                                break;
                        }
                        reject(new Error(message));
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000 // 5 minutes
                    }
                );
            });
        },
        
        async reverseGeocode(lat, lng) {
            const response = await fetch(
                `/api/location/reverse?lat=${lat}&lng=${lng}`
            );
            
            if (!response.ok) {
                throw new Error('Failed to get location details');
            }
            
            return await response.json();
        },
        
        async saveToBackend(location) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            await fetch('/api/location/detect', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(location)
            });
        },
        
        reloadWithLocation() {
            const url = new URL(window.location);
            url.searchParams.set('lat', this.location.latitude);
            url.searchParams.set('lng', this.location.longitude);
            window.location.href = url.toString();
        },
        
        clearLocation() {
            localStorage.removeItem('userLocation');
            this.location = null;
            this.detected = false;
            window.location.reload();
        }
    }
}
```

---

## 5. Blade Component - Car Card

```blade
{{-- resources/views/components/car-card.blade.php --}}

@props(['car', 'showDistance' => false])

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
    {{-- Image --}}
    <div class="relative h-48 overflow-hidden">
        <a href="{{ route('cars.show', $car->slug) }}">
            <img 
                src="{{ $car->main_image }}" 
                alt="{{ $car->title }}"
                class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                loading="lazy"
            >
        </a>
        
        @if($car->is_featured)
            <span class="absolute top-2 left-2 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                Featured
            </span>
        @endif
        
        @if($car->condition === 'new')
            <span class="absolute top-2 right-2 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                New
            </span>
        @endif
        
        {{-- Favorite button --}}
        @auth
            <button 
                x-data="favoriteButton({{ $car->id }}, {{ $car->isFavoritedBy(auth()->user()) ? 'true' : 'false' }})"
                @click="toggle"
                class="absolute bottom-2 right-2 p-2 bg-white rounded-full shadow-lg hover:bg-gray-100"
                :class="{ 'text-red-500': favorited, 'text-gray-400': !favorited }"
            >
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                </svg>
            </button>
        @endauth
    </div>
    
    {{-- Content --}}
    <div class="p-4">
        {{-- Title --}}
        <a href="{{ route('cars.show', $car->slug) }}" class="block">
            <h3 class="text-lg font-semibold text-gray-900 hover:text-blue-600 truncate">
                {{ $car->title }}
            </h3>
        </a>
        
        {{-- Price --}}
        <p class="text-2xl font-bold text-blue-600 mt-2">
            {{ $car->formatted_price }}
        </p>
        
        {{-- Details --}}
        <div class="mt-3 flex items-center text-sm text-gray-600 space-x-4">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                </svg>
                {{ number_format($car->mileage) }} km
            </span>
            
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                {{ $car->year }}
            </span>
            
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3z"/>
                </svg>
                {{ ucfirst($car->transmission) }}
            </span>
        </div>
        
        {{-- Location & Distance --}}
        @if($showDistance && isset($car->distance))
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                {{ number_format($car->distance, 1) }} km away • {{ $car->city }}
            </div>
        @else
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                {{ $car->city }}, {{ $car->country }}
            </div>
        @endif
        
        {{-- Contact Button --}}
        <a 
            href="{{ $car->whatsapp_link }}" 
            target="_blank"
            class="mt-4 block w-full bg-green-500 hover:bg-green-600 text-white text-center py-2 rounded-lg font-semibold transition-colors"
        >
            <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            Contact via WhatsApp
        </a>
    </div>
</div>

<script>
function favoriteButton(carId, isFavorited) {
    return {
        favorited: isFavorited,
        async toggle() {
            const url = `/favorites/${carId}`;
            const method = this.favorited ? 'DELETE' : 'POST';
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                
                if (response.ok) {
                    this.favorited = !this.favorited;
                }
            } catch (error) {
                console.error('Error toggling favorite:', error);
            }
        }
    }
}
</script>
```

---

## 6. SEO Helper Service

```php
<?php
// app/Services/SeoService.php

namespace App\Services;

use App\Models\Car;

class SeoService
{
    public function generateCarMeta(Car $car)
    {
        $title = $car->meta_title ?: 
            "{$car->year} {$car->make} {$car->model} for Sale - {$car->formatted_price}";
        
        $description = $car->meta_description ?: 
            "Buy {$car->year} {$car->make} {$car->model} in {$car->city}. " .
            "{$car->mileage} km, {$car->transmission} transmission, {$car->fuel_type}. " .
            "Price: {$car->formatted_price}. Contact via WhatsApp.";
        
        $keywords = implode(', ', [
            $car->make,
            $car->model,
            $car->year,
            $car->condition . ' car',
            $car->city . ' cars',
            'buy ' . $car->make,
            'used cars ' . $car->city
        ]);
        
        return [
            'title' => $title,
            'description' => substr($description, 0, 160),
            'keywords' => $keywords,
            'og_image' => $car->main_image,
        ];
    }
    
    public function generateStructuredData(Car $car)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Car',
            'name' => $car->title,
            'description' => $car->description,
            'brand' => [
                '@type' => 'Brand',
                'name' => $car->make
            ],
            'model' => $car->model,
            'productionDate' => $car->year,
            'vehicleConfiguration' => $car->trim,
            'mileageFromOdometer' => [
                '@type' => 'QuantitativeValue',
                'value' => $car->mileage,
                'unitCode' => 'KMT'
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $car->price,
                'priceCurrency' => 'AED',
                'availability' => 'https://schema.org/InStock',
                'url' => route('cars.show', $car->slug),
                'seller' => [
                    '@type' => 'Organization',
                    'name' => config('app.name')
                ]
            ],
            'image' => $car->getMedia('images')->map->getUrl(),
            'vehicleTransmission' => ucfirst($car->transmission),
            'fuelType' => ucfirst($car->fuel_type),
            'bodyType' => ucfirst($car->body_type),
            'color' => $car->exterior_color,
            'numberOfDoors' => $car->doors,
            'seatingCapacity' => $car->seats,
        ];
    }
}
```

These code examples provide a solid foundation for implementing the car selling web application with all the essential features mentioned in the main prompt!
