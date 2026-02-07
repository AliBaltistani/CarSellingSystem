<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\AttributeOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttributeImportExportController extends Controller
{
    /**
     * Export all attributes to JSON
     */
    public function export()
    {
        $data = [
            'exported_at' => now()->toIso8601String(),
            'groups' => AttributeGroup::orderBy('order')->get()->map(fn($g) => [
                'name' => $g->name,
                'slug' => $g->slug,
                'description' => $g->description,
                'icon' => $g->icon,
                'order' => $g->order,
                'is_active' => $g->is_active,
            ])->toArray(),
            'attributes' => Attribute::with(['group', 'options', 'categories'])
                ->orderBy('order')
                ->get()
                ->map(fn($a) => [
                    'name' => $a->name,
                    'slug' => $a->slug,
                    'group' => $a->group?->slug,
                    'type' => $a->type,
                    'is_required' => $a->is_required,
                    'validation_rules' => $a->validation_rules,
                    'show_in_filters' => $a->show_in_filters,
                    'show_in_card' => $a->show_in_card,
                    'show_in_details' => $a->show_in_details,
                    'show_in_comparison' => $a->show_in_comparison,
                    'placeholder' => $a->placeholder,
                    'help_text' => $a->help_text,
                    'suffix' => $a->suffix,
                    'prefix' => $a->prefix,
                    'icon' => $a->icon,
                    'default_value' => $a->default_value,
                    'min_value' => $a->min_value,
                    'max_value' => $a->max_value,
                    'step' => $a->step,
                    'is_active' => $a->is_active,
                    'order' => $a->order,
                    'options' => $a->options->map(fn($o) => [
                        'label' => $o->label,
                        'value' => $o->value,
                        'color' => $o->color,
                        'icon' => $o->icon,
                        'is_default' => $o->is_default,
                        'order' => $o->order,
                    ])->toArray(),
                    'categories' => $a->categories->pluck('slug')->toArray(),
                ])->toArray(),
        ];

        $filename = 'attributes_export_' . now()->format('Y-m-d_His') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Content-Type', 'application/json');
    }

    /**
     * Export attributes to CSV (simplified format)
     */
    public function exportCsv()
    {
        $attributes = Attribute::with(['group', 'options'])
            ->orderBy('order')
            ->get();

        $csvContent = "Name,Slug,Group,Type,Required,Show in Filters,Show in Card,Show in Details,Placeholder,Options\n";
        
        foreach ($attributes as $attr) {
            $options = $attr->options->pluck('label')->implode('|');
            $csvContent .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",%s,%s,%s,%s,\"%s\",\"%s\"\n",
                str_replace('"', '""', $attr->name),
                $attr->slug,
                $attr->group?->name ?? '',
                $attr->type,
                $attr->is_required ? '1' : '0',
                $attr->show_in_filters ? '1' : '0',
                $attr->show_in_card ? '1' : '0',
                $attr->show_in_details ? '1' : '0',
                str_replace('"', '""', $attr->placeholder ?? ''),
                str_replace('"', '""', $options)
            );
        }

        $filename = 'attributes_export_' . now()->format('Y-m-d_His') . '.csv';
        
        return response($csvContent)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Content-Type', 'text/csv');
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('admin.attributes.import');
    }

    /**
     * Import attributes from JSON file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,txt|max:2048',
            'mode' => 'required|in:merge,replace',
        ]);

        try {
            $content = file_get_contents($request->file('file')->path());
            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Invalid JSON file: ' . json_last_error_msg());
            }

            $stats = ['groups' => 0, 'attributes' => 0, 'options' => 0];

            DB::beginTransaction();

            // Import groups
            if (!empty($data['groups'])) {
                foreach ($data['groups'] as $groupData) {
                    $group = AttributeGroup::where('slug', $groupData['slug'])->first();
                    
                    if ($group && $request->mode === 'merge') {
                        $group->update($groupData);
                    } else {
                        AttributeGroup::updateOrCreate(
                            ['slug' => $groupData['slug']],
                            $groupData
                        );
                    }
                    $stats['groups']++;
                }
            }

            // Import attributes
            if (!empty($data['attributes'])) {
                foreach ($data['attributes'] as $attrData) {
                    $options = $attrData['options'] ?? [];
                    $categories = $attrData['categories'] ?? [];
                    unset($attrData['options'], $attrData['categories']);

                    // Find group ID
                    if (!empty($attrData['group'])) {
                        $group = AttributeGroup::where('slug', $attrData['group'])->first();
                        $attrData['attribute_group_id'] = $group?->id;
                    }
                    unset($attrData['group']);

                    $attribute = Attribute::updateOrCreate(
                        ['slug' => $attrData['slug']],
                        $attrData
                    );
                    $stats['attributes']++;

                    // Import options
                    if ($request->mode === 'replace') {
                        $attribute->options()->delete();
                    }
                    
                    foreach ($options as $optionData) {
                        AttributeOption::updateOrCreate(
                            [
                                'attribute_id' => $attribute->id,
                                'value' => $optionData['value'],
                            ],
                            array_merge($optionData, ['attribute_id' => $attribute->id])
                        );
                        $stats['options']++;
                    }

                    // Sync categories
                    if (!empty($categories)) {
                        $categoryIds = \App\Models\Category::whereIn('slug', $categories)->pluck('id');
                        $attribute->categories()->syncWithoutDetaching($categoryIds);
                    }
                }
            }

            DB::commit();

            return back()->with('success', sprintf(
                'Import successful! Imported: %d groups, %d attributes, %d options.',
                $stats['groups'],
                $stats['attributes'],
                $stats['options']
            ));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
