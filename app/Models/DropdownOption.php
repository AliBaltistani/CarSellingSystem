<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class DropdownOption extends Model
{
    use HasFactory;

    // Dropdown type constants
    const TYPE_MAKE = 'make';
    const TYPE_MODEL = 'model';
    const TYPE_CONDITION = 'condition';
    const TYPE_TRANSMISSION = 'transmission';
    const TYPE_FUEL_TYPE = 'fuel_type';
    const TYPE_BODY_TYPE = 'body_type';
    const TYPE_DRIVETRAIN = 'drivetrain';
    const TYPE_EXTERIOR_COLOR = 'exterior_color';
    const TYPE_INTERIOR_COLOR = 'interior_color';
    const TYPE_DOORS = 'doors';
    const TYPE_SEATS = 'seats';
    const TYPE_CYLINDERS = 'cylinders';

    protected $fillable = [
        'type',
        'slug',
        'label',
        'value',
        'icon',
        'color',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($option) {
            if (empty($option->slug)) {
                $option->slug = Str::slug($option->label);
            }
            if (empty($option->value)) {
                $option->value = $option->slug;
            }
        });

        // Clear cache when dropdown options change
        static::saved(function ($option) {
            Cache::forget("dropdown_options_{$option->type}");
        });

        static::deleted(function ($option) {
            Cache::forget("dropdown_options_{$option->type}");
        });
    }

    /**
     * Get all dropdown types with their labels
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_MAKE => 'Car Makes (Brands)',
            self::TYPE_MODEL => 'Car Models',
            self::TYPE_CONDITION => 'Vehicle Condition',
            self::TYPE_TRANSMISSION => 'Transmission Types',
            self::TYPE_FUEL_TYPE => 'Fuel Types',
            self::TYPE_BODY_TYPE => 'Body Types',
            self::TYPE_DRIVETRAIN => 'Drivetrain',
            self::TYPE_EXTERIOR_COLOR => 'Exterior Colors',
            self::TYPE_INTERIOR_COLOR => 'Interior Colors',
            self::TYPE_DOORS => 'Number of Doors',
            self::TYPE_SEATS => 'Number of Seats',
            self::TYPE_CYLINDERS => 'Number of Cylinders',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(DropdownOption::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(DropdownOption::class, 'parent_id');
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    /**
     * Get options by type (cached)
     */
    public static function byType(string $type): Collection
    {
        return Cache::remember("dropdown_options_{$type}", 3600, function () use ($type) {
            return static::where('type', $type)
                ->where('is_active', true)
                ->orderBy('order')
                ->orderBy('label')
                ->get();
        });
    }

    /**
     * Get options for dropdown (returns value => label pairs)
     */
    public static function forSelect(string $type): array
    {
        return static::byType($type)
            ->pluck('label', 'value')
            ->toArray();
    }

    /**
     * Clear cache for a specific type
     */
    public static function clearCache(string $type = null): void
    {
        if ($type) {
            Cache::forget("dropdown_options_{$type}");
        } else {
            foreach (array_keys(self::getTypes()) as $t) {
                Cache::forget("dropdown_options_{$t}");
            }
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('label');
    }
}
