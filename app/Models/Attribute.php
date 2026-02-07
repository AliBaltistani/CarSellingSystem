<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Attribute extends Model
{
    use HasFactory;

    // Available field types
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_NUMBER = 'number';
    const TYPE_SELECT = 'select';
    const TYPE_MULTISELECT = 'multiselect';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_DATE = 'date';
    const TYPE_RANGE = 'range';
    const TYPE_COLOR = 'color';

    const TYPES = [
        self::TYPE_TEXT => 'Text Input',
        self::TYPE_TEXTAREA => 'Text Area',
        self::TYPE_NUMBER => 'Number',
        self::TYPE_SELECT => 'Dropdown Select',
        self::TYPE_MULTISELECT => 'Multi-Select',
        self::TYPE_BOOLEAN => 'Yes/No Toggle',
        self::TYPE_DATE => 'Date Picker',
        self::TYPE_RANGE => 'Range Slider',
        self::TYPE_COLOR => 'Color Picker',
    ];

    protected $fillable = [
        'name',
        'slug',
        'attribute_group_id',
        'type',
        'is_required',
        'validation_rules',
        'show_in_filters',
        'show_in_card',
        'show_in_details',
        'show_in_comparison',
        'placeholder',
        'help_text',
        'suffix',
        'prefix',
        'icon',
        'default_value',
        'min_value',
        'max_value',
        'step',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'show_in_filters' => 'boolean',
        'show_in_card' => 'boolean',
        'show_in_details' => 'boolean',
        'show_in_comparison' => 'boolean',
        'is_active' => 'boolean',
        'min_value' => 'decimal:2',
        'max_value' => 'decimal:2',
        'step' => 'decimal:2',
        'order' => 'integer',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($attribute) {
            if (empty($attribute->slug)) {
                $attribute->slug = Str::slug($attribute->name);
            }
        });
    }

    // Relationships
    public function group(): BelongsTo
    {
        return $this->belongsTo(AttributeGroup::class, 'attribute_group_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class)->orderBy('order');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_attributes')
            ->withPivot(['is_required', 'show_in_filters', 'show_in_card', 'show_in_details', 'order'])
            ->withTimestamps();
    }

    public function values(): HasMany
    {
        return $this->hasMany(CarAttributeValue::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    public function scopeFilterable($query)
    {
        return $query->where('show_in_filters', true);
    }

    public function scopeForCard($query)
    {
        return $query->where('show_in_card', true);
    }

    // Helpers
    public function hasOptions(): bool
    {
        return in_array($this->type, [self::TYPE_SELECT, self::TYPE_MULTISELECT, self::TYPE_COLOR]);
    }

    public function isNumeric(): bool
    {
        return in_array($this->type, [self::TYPE_NUMBER, self::TYPE_RANGE]);
    }

    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    // Get formatted value for display
    public function formatValue($value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        $formatted = $value;

        // Handle boolean
        if ($this->type === self::TYPE_BOOLEAN) {
            return $value ? 'Yes' : 'No';
        }

        // Handle multiselect (JSON array)
        if ($this->type === self::TYPE_MULTISELECT && is_array($value)) {
            $labels = $this->options()
                ->whereIn('value', $value)
                ->pluck('label')
                ->toArray();
            return implode(', ', $labels);
        }

        // Handle select - get label
        if ($this->type === self::TYPE_SELECT) {
            $option = $this->options()->where('value', $value)->first();
            $formatted = $option ? $option->label : $value;
        }

        // Add prefix/suffix
        if ($this->prefix) {
            $formatted = $this->prefix . ' ' . $formatted;
        }
        if ($this->suffix) {
            $formatted = $formatted . ' ' . $this->suffix;
        }

        return (string) $formatted;
    }

    // Get attributes for a category
    public static function getForCategory(int $categoryId)
    {
        return static::active()
            ->whereHas('categories', fn($q) => $q->where('category_id', $categoryId))
            ->with(['options', 'group'])
            ->ordered()
            ->get();
    }
}
