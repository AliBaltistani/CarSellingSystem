<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeGroupController extends Controller
{
    public function index()
    {
        $groups = AttributeGroup::withCount('attributes')
            ->ordered()
            ->paginate(15);
            
        return view('admin.attribute-groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.attribute-groups.form', ['group' => new AttributeGroup()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:attribute_groups,slug',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        AttributeGroup::create($validated);

        return redirect()->route('admin.attribute-groups.index')
            ->with('success', 'Attribute group created successfully.');
    }

    public function edit(AttributeGroup $attribute_group)
    {
        return view('admin.attribute-groups.form', ['group' => $attribute_group]);
    }

    public function update(Request $request, AttributeGroup $attribute_group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:attribute_groups,slug,' . $attribute_group->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $attribute_group->update($validated);

        return redirect()->route('admin.attribute-groups.index')
            ->with('success', 'Attribute group updated successfully.');
    }

    public function destroy(AttributeGroup $attribute_group)
    {
        if ($attribute_group->attributes()->count() > 0) {
            return back()->with('error', 'Cannot delete group with existing attributes. Remove attributes first.');
        }

        $attribute_group->delete();

        return redirect()->route('admin.attribute-groups.index')
            ->with('success', 'Attribute group deleted successfully.');
    }

    public function toggleActive(AttributeGroup $attribute_group)
    {
        $attribute_group->update(['is_active' => !$attribute_group->is_active]);
        
        return back()->with('success', 
            'Group ' . ($attribute_group->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }
}
