<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Car extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'negotiable',
        'year',
        'make',
        'model',
        'trim',
        'vin',
        'condition',
        'mileage',
        'registration_number',
        'transmission',
        'fuel_type',
        'engine_capacity',
        'horsepower',
        'cylinders',
        'body_type',
        'exterior_color',
        'interior_color',
        'doors',
        'seats',
        'drivetrain',
        'features',
        'latitude',
        'longitude',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'whatsapp_number',
        'phone_number',
        'email',
        'status',
        'is_published',
        'is_featured',
        'featured_until',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
        'sold_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'year' => 'integer',
        'mileage' => 'integer',
        'horsepower' => 'integer',
        'cylinders' => 'integer',
        'doors' => 'integer',
        'seats' => 'integer',
        'negotiable' => 'boolean',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'features' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'featured_until' => 'datetime',
        'published_at' => 'datetime',
        'sold_at' => 'datetime',
    ];

    protected $appends = ['formatted_price', 'main_image', 'whatsapp_link'];

    // Slug configuration
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    // Route model binding by slug
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderBy('order');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(CarView::class);
    }

    // Accessors
    public function getFormattedPriceAttribute(): string
    {
        return 'AED ' . number_format($this->price, 0);
    }

    public function getMainImageAttribute(): string
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            return asset('storage/' . $primaryImage->path);
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->path);
        }
        
        return asset('images/image_placeholder.png');
    }

    public function getWhatsappLinkAttribute(): string
    {
        $message = urlencode("Hi, I'm interested in {$this->title}");
        $number = preg_replace('/[^0-9]/', '', $this->whatsapp_number);
        return "https://wa.me/{$number}?text={$message}";
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)
            ->where(function ($q) {
                $q->whereNull('featured_until')
                  ->orWhere('featured_until', '>', now());
            });
    }

    public function scopeNearby(Builder $query, float $latitude, float $longitude, int $radiusKm = 50): Builder
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

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['make'] ?? null, fn($q, $make) => $q->where('make', $make))
            ->when($filters['model'] ?? null, fn($q, $model) => $q->where('model', $model))
            ->when($filters['min_price'] ?? null, fn($q, $minPrice) => $q->where('price', '>=', $minPrice))
            ->when($filters['max_price'] ?? null, fn($q, $maxPrice) => $q->where('price', '<=', $maxPrice))
            ->when($filters['min_year'] ?? null, fn($q, $minYear) => $q->where('year', '>=', $minYear))
            ->when($filters['max_year'] ?? null, fn($q, $maxYear) => $q->where('year', '<=', $maxYear))
            ->when($filters['condition'] ?? null, fn($q, $condition) => $q->where('condition', $condition))
            ->when($filters['transmission'] ?? null, fn($q, $trans) => $q->where('transmission', $trans))
            ->when($filters['fuel_type'] ?? null, fn($q, $fuel) => $q->where('fuel_type', $fuel))
            ->when($filters['body_type'] ?? null, fn($q, $body) => $q->where('body_type', $body))
            ->when($filters['category'] ?? null, fn($q, $cat) => 
                $q->whereHas('category', fn($sq) => $sq->where('slug', $cat))
            )
            ->when($filters['search'] ?? null, fn($q, $search) => 
                $q->where(function ($sq) use ($search) {
                    $sq->where('title', 'like', "%{$search}%")
                       ->orWhere('description', 'like', "%{$search}%")
                       ->orWhere('make', 'like', "%{$search}%")
                       ->orWhere('model', 'like', "%{$search}%");
                })
            );
    }

    // Methods
    public function incrementViews(): void
    {
        $this->increment('views_count');
        
        $this->views()->create([
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function isFavoritedBy($user): bool
    {
        if (!$user) return false;
        return $this->favorites()->where('user_id', $user->id)->exists();
    }
}
