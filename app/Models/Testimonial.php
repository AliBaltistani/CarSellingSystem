<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_photo',
        'designation',
        'rating',
        'review',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderByDesc('created_at');
    }

    // Accessor for photo URL
    public function getPhotoUrlAttribute(): string
    {
        if ($this->customer_photo) {
            return asset('storage/' . $this->customer_photo);
        }
        
        // Default avatar based on initials
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->customer_name) . '&background=f59e0b&color=fff&size=80';
    }

    // Get display testimonials for frontend
    public static function getDisplayTestimonials(int $limit = 6)
    {
        return static::active()
            ->ordered()
            ->limit($limit)
            ->get();
    }
}
