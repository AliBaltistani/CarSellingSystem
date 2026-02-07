<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'label',
        'value',
        'color',
        'icon',
        'is_default',
        'order',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'order' => 'integer',
    ];

    // Relationships
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('label');
    }

    public function scopeDefaults($query)
    {
        return $query->where('is_default', true);
    }
}
