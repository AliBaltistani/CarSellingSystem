<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryAttributeController extends Controller
{
    /**
     * Get attributes for a specific category (by ID)
     */
    public function index($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        // Get attributes assigned to this category, grouped by their group
        $attributes = $category->getAttributesWithOptions();

        // Group by attribute group
        $grouped = $attributes->groupBy(function($attr) {
            return $attr->group?->name ?? 'General';
        });

        // Format for JS consumption
        $result = [];
        foreach ($grouped as $groupName => $attrs) {
            $result[] = [
                'group' => $groupName,
                'group_icon' => $attrs->first()->group?->icon ?? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                'attributes' => $attrs->map(function($attr) {
                    return [
                        'id' => $attr->id,
                        'name' => $attr->name,
                        'slug' => $attr->slug,
                        'type' => $attr->type,
                        'is_required' => (bool) $attr->pivot?->is_required ?? $attr->is_required,
                        'placeholder' => $attr->placeholder,
                        'help_text' => $attr->help_text,
                        'prefix' => $attr->prefix,
                        'suffix' => $attr->suffix,
                        'icon' => $attr->icon,
                        'default_value' => $attr->default_value,
                        'min_value' => $attr->min_value,
                        'max_value' => $attr->max_value,
                        'step' => $attr->step,
                        'options' => $attr->hasOptions() ? $attr->options->map(fn($o) => [
                            'label' => $o->label,
                            'value' => $o->value,
                            'color' => $o->color,
                            'icon' => $o->icon,
                            'is_default' => $o->is_default,
                        ])->values() : [],
                    ];
                })->values(),
            ];
        }

        return response()->json($result);
    }
}
