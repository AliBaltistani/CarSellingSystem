<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'state',
        'country',
        'display_name',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    /**
     * Get formatted display name
     */
    public function getFormattedNameAttribute(): string
    {
        if ($this->display_name) {
            return $this->display_name;
        }

        $parts = array_filter([$this->city, $this->state, $this->country]);
        return implode(', ', $parts);
    }

    /**
     * Scope for active locations only
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for searching locations
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('city', 'like', "%{$search}%")
              ->orWhere('state', 'like', "%{$search}%")
              ->orWhere('country', 'like', "%{$search}%")
              ->orWhere('display_name', 'like', "%{$search}%");
        });
    }

    /**
     * Find or create a location from API data
     */
    public static function findOrCreateFromApi(array $data): self
    {
        $city = $data['city'] ?? $data['name'] ?? 'Unknown';
        $country = $data['country'] ?? 'Unknown';

        return self::firstOrCreate(
            ['city' => $city, 'country' => $country],
            [
                'state' => $data['state'] ?? null,
                'display_name' => $data['display_name'] ?? null,
                'latitude' => $data['lat'] ?? null,
                'longitude' => $data['lon'] ?? null,
                'is_active' => true,
            ]
        );
    }

    /**
     * Relationship with cars (if needed)
     */
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
