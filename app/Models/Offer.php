<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'icon',
        'image',
        'badge',
        'price_label',
        'price_from',
        'price_upgrade',
        'description',
        'features',
        'cta_text',
        'cta_link',
        'stripe_price_id',
        'is_active',
        'is_featured',
        'order',
        'expires_at',
    ];

    protected $casts = [
        'features' => 'array',
        'price_from' => 'decimal:2',
        'price_upgrade' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'expires_at' => 'date',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderByDesc('created_at');
    }

    // Accessor for image URL
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    // Accessor for icon URL
    public function getIconUrlAttribute(): ?string
    {
        if ($this->icon) {
            return asset('storage/' . $this->icon);
        }
        return null;
    }

    // Check if offer is expired
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    // Get display offers for frontend
    public static function getDisplayOffers(int $limit = 6)
    {
        return static::active()
            ->ordered()
            ->limit($limit)
            ->get();
    }

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
