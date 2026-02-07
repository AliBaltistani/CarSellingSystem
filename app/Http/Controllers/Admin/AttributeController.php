<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\AttributeOption;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function index(Request $request)
    {
        $groups = AttributeGroup::ordered()->get();
        
        $attributes = Attribute::with('group')
            ->when($request->group, fn($q, $group) => $q->where('attribute_group_id', $group))
            ->when($request->type, fn($q, $type) => $q->where('type', $type))
            ->ordered()
            ->paginate(20);
            
        return view('admin.attributes.index', compact('attributes', 'groups'));
    }

    public function create()
    {
        $groups = AttributeGroup::active()->ordered()->get();
        $types = Attribute::TYPES;
        
        return view('admin.attributes.form', [
            'attribute' => new Attribute(),
            'groups' => $groups,
            'types' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateAttribute($request);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $attribute = Attribute::create($validated);

        // Handle options for select/multiselect types
        if ($attribute->hasOptions() && $request->has('options')) {
            $this->syncOptions($attribute, $request->input('options', []));
        }

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute created successfully.');
    }

    public function edit(Attribute $attribute)
    {
        $groups = AttributeGroup::active()->ordered()->get();
        $types = Attribute::TYPES;
        $attribute->load('options');
        
        return view('admin.attributes.form', compact('attribute', 'groups', 'types'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $this->validateAttribute($request, $attribute);
        
        $attribute->update($validated);

        // Handle options for select/multiselect types
        if ($attribute->hasOptions()) {
            $this->syncOptions($attribute, $request->input('options', []));
        } else {
            // Remove options if type changed to non-option type
            $attribute->options()->delete();
        }

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute updated successfully.');
    }

    public function destroy(Attribute $attribute)
    {
        // Check if attribute has values
        if ($attribute->values()->count() > 0) {
            return back()->with('error', 'Cannot delete attribute with existing values. Remove values from cars first.');
        }

        $attribute->delete();

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute deleted successfully.');
    }

    public function toggleActive(Attribute $attribute)
    {
        $attribute->update(['is_active' => !$attribute->is_active]);
        
        return back()->with('success', 
            'Attribute ' . ($attribute->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }

    // Category assignment page
    public function categories(Attribute $attribute)
    {
        $categories = Category::active()->ordered()->get();
        $assignedIds = $attribute->categories->pluck('id')->toArray();
        
        return view('admin.attributes.categories', compact('attribute', 'categories', 'assignedIds'));
    }

    // Assign/unassign attribute to categories
    public function updateCategories(Request $request, Attribute $attribute)
    {
        $categoryIds = $request->input('categories', []);
        
        // Sync categories with pivot data
        $syncData = [];
        foreach ($categoryIds as $categoryId) {
            $syncData[$categoryId] = [
                'order' => $request->input("order.{$categoryId}", 0),
                'is_required' => $request->has("is_required.{$categoryId}"),
                'show_in_filters' => $request->has("show_in_filters.{$categoryId}"),
                'show_in_card' => $request->has("show_in_card.{$categoryId}"),
            ];
        }
        
        $attribute->categories()->sync($syncData);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Categories updated successfully.');
    }

    // Validation rules
    protected function validateAttribute(Request $request, ?Attribute $attribute = null): array
    {
        $uniqueSlug = $attribute ? 'unique:attributes,slug,' . $attribute->id : 'unique:attributes,slug';
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|' . $uniqueSlug,
            'attribute_group_id' => 'nullable|exists:attribute_groups,id',
            'type' => 'required|in:' . implode(',', array_keys(Attribute::TYPES)),
            'is_required' => 'boolean',
            'validation_rules' => 'nullable|string|max:255',
            'show_in_filters' => 'boolean',
            'show_in_card' => 'boolean',
            'show_in_details' => 'boolean',
            'show_in_comparison' => 'boolean',
            'placeholder' => 'nullable|string|max:255',
            'help_text' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'prefix' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:255',
            'default_value' => 'nullable|string|max:255',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
            'step' => 'nullable|numeric',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        // Set boolean defaults
        $validated['is_required'] = $request->has('is_required');
        $validated['show_in_filters'] = $request->has('show_in_filters');
        $validated['show_in_card'] = $request->has('show_in_card');
        $validated['show_in_details'] = $request->has('show_in_details') || !$attribute;
        $validated['show_in_comparison'] = $request->has('show_in_comparison');
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        return $validated;
    }

    // Sync options for select/multiselect types
    protected function syncOptions(Attribute $attribute, array $options): void
    {
        $existingIds = [];
        
        foreach ($options as $index => $option) {
            if (empty($option['label'])) continue;
            
            $optionData = [
                'label' => $option['label'],
                'value' => $option['value'] ?? Str::slug($option['label']),
                'color' => $option['color'] ?? null,
                'icon' => $option['icon'] ?? null,
                'is_default' => isset($option['is_default']) && $option['is_default'],
                'order' => $index,
            ];

            if (!empty($option['id'])) {
                // Update existing
                $attrOption = AttributeOption::find($option['id']);
                if ($attrOption) {
                    $attrOption->update($optionData);
                    $existingIds[] = $attrOption->id;
                }
            } else {
                // Create new
                $newOption = $attribute->options()->create($optionData);
                $existingIds[] = $newOption->id;
            }
        }

        // Delete removed options
        $attribute->options()->whereNotIn('id', $existingIds)->delete();
    }
}
