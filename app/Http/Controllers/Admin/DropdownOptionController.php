<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DropdownOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DropdownOptionController extends Controller
{
    public function index(Request $request)
    {
        $types = DropdownOption::getTypes();
        $selectedType = $request->get('type');

        $query = DropdownOption::query();

        if ($selectedType) {
            $query->where('type', $selectedType);
        }

        $options = $query->orderBy('type')
            ->orderBy('order')
            ->orderBy('label')
            ->paginate(50)
            ->withQueryString();

        return view('admin.dropdown-options.index', compact('options', 'types', 'selectedType'));
    }

    public function create()
    {
        $types = DropdownOption::getTypes();
        return view('admin.dropdown-options.form', [
            'option' => null,
            'types' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:50', Rule::in(array_keys(DropdownOption::getTypes()))],
            'label' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:100'],
            'value' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:1000'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['label']);
        }

        // Set default value to slug if not provided
        if (empty($validated['value'])) {
            $validated['value'] = $validated['slug'];
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        // Check for duplicate
        $exists = DropdownOption::where('type', $validated['type'])
            ->where('slug', $validated['slug'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['slug' => 'This slug already exists for this type.']);
        }

        DropdownOption::create($validated);

        return redirect()->route('admin.dropdown-options.index', ['type' => $validated['type']])
            ->with('success', 'Dropdown option created successfully.');
    }

    public function edit(DropdownOption $dropdownOption)
    {
        $types = DropdownOption::getTypes();
        return view('admin.dropdown-options.form', [
            'option' => $dropdownOption,
            'types' => $types,
        ]);
    }

    public function update(Request $request, DropdownOption $dropdownOption)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:50', Rule::in(array_keys(DropdownOption::getTypes()))],
            'label' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:100'],
            'value' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:1000'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['label']);
        }

        // Set default value to slug if not provided
        if (empty($validated['value'])) {
            $validated['value'] = $validated['slug'];
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        // Check for duplicate (excluding current)
        $exists = DropdownOption::where('type', $validated['type'])
            ->where('slug', $validated['slug'])
            ->where('id', '!=', $dropdownOption->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['slug' => 'This slug already exists for this type.']);
        }

        $dropdownOption->update($validated);

        return redirect()->route('admin.dropdown-options.index', ['type' => $validated['type']])
            ->with('success', 'Dropdown option updated successfully.');
    }

    public function destroy(DropdownOption $dropdownOption)
    {
        $type = $dropdownOption->type;
        $dropdownOption->delete();

        return redirect()->route('admin.dropdown-options.index', ['type' => $type])
            ->with('success', 'Dropdown option deleted successfully.');
    }

    public function toggleActive(DropdownOption $dropdownOption)
    {
        $dropdownOption->update(['is_active' => !$dropdownOption->is_active]);

        return back()->with('success', 'Status updated successfully.');
    }
}
