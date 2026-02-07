<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'attribute_id',
        'value',
        'numeric_value',
    ];

    protected $casts = [
        'numeric_value' => 'decimal:4',
    ];

    // Relationships
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    // Get typed value based on attribute type
    public function getTypedValueAttribute()
    {
        if (!$this->attribute) {
            return $this->value;
        }

        switch ($this->attribute->type) {
            case Attribute::TYPE_BOOLEAN:
                return filter_var($this->value, FILTER_VALIDATE_BOOLEAN);
            
            case Attribute::TYPE_NUMBER:
            case Attribute::TYPE_RANGE:
                return $this->numeric_value ?? (float) $this->value;
            
            case Attribute::TYPE_MULTISELECT:
                return json_decode($this->value, true) ?? [];
            
            default:
                return $this->value;
        }
    }

    // Get formatted display value
    public function getFormattedValueAttribute(): string
    {
        if (!$this->attribute) {
            return (string) $this->value;
        }

        return $this->attribute->formatValue($this->typed_value);
    }

    // Set value with automatic numeric extraction
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = is_array($value) ? json_encode($value) : $value;
        
        // Extract numeric value for filtering
        if (is_numeric($value)) {
            $this->attributes['numeric_value'] = (float) $value;
        } else {
            $this->attributes['numeric_value'] = null;
        }
    }

    // Static: Bulk save values for a car
    public static function saveForCar(Car $car, array $attributeValues): void
    {
        foreach ($attributeValues as $attributeId => $value) {
            // Skip empty values
            if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                // Delete existing value
                static::where('car_id', $car->id)
                    ->where('attribute_id', $attributeId)
                    ->delete();
                continue;
            }

            static::updateOrCreate(
                [
                    'car_id' => $car->id,
                    'attribute_id' => $attributeId,
                ],
                [
                    'value' => $value,
                ]
            );
        }
    }
}
