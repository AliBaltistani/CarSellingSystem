<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedSearch extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'filters',
        'notify_new_results',
    ];

    protected $casts = [
        'filters' => 'array',
        'notify_new_results' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Get the URL with filters applied
    public function getSearchUrlAttribute(): string
    {
        return route('cars.index', $this->filters);
    }
}
