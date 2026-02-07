<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarImage extends Model
{
    protected $fillable = [
        'car_id',
        'path',
        'thumbnail_path',
        'title',
        'alt_text',
        'order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer',
    ];

    // Relationships
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    // Accessors
    public function getUrlAttribute(): string
    {
        $storagePath = storage_path('app/public/' . $this->path);
        if ($this->path && file_exists($storagePath)) {
            return asset('storage/' . $this->path);
        }
        return asset('images/image_placeholder.png');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path 
            ? asset('storage/' . $this->thumbnail_path)
            : $this->url;
    }
}
