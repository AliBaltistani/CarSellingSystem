<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Category;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Create Attribute Groups
        $groups = [
            ['name' => 'Engine & Performance', 'slug' => 'engine-performance', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>', 'order' => 1],
            ['name' => 'Exterior', 'slug' => 'exterior', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>', 'order' => 2],
            ['name' => 'Interior & Comfort', 'slug' => 'interior-comfort', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>', 'order' => 3],
            ['name' => 'Safety & Security', 'slug' => 'safety-security', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>', 'order' => 4],
            ['name' => 'Technology', 'slug' => 'technology', 'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>', 'order' => 5],
        ];

        foreach ($groups as $groupData) {
            AttributeGroup::create($groupData);
        }

        // Get groups for reference
        $engineGroup = AttributeGroup::where('slug', 'engine-performance')->first();
        $exteriorGroup = AttributeGroup::where('slug', 'exterior')->first();
        $interiorGroup = AttributeGroup::where('slug', 'interior-comfort')->first();
        $safetyGroup = AttributeGroup::where('slug', 'safety-security')->first();
        $techGroup = AttributeGroup::where('slug', 'technology')->first();

        // Create sample attributes
        $attributes = [
            // Engine & Performance
            [
                'name' => 'Turbo/Supercharged',
                'slug' => 'turbo-supercharged',
                'attribute_group_id' => $engineGroup->id,
                'type' => 'select',
                'show_in_filters' => true,
                'show_in_card' => true,
                'show_in_details' => true,
                'options' => [
                    ['label' => 'Naturally Aspirated', 'value' => 'na'],
                    ['label' => 'Turbocharged', 'value' => 'turbo'],
                    ['label' => 'Twin-Turbo', 'value' => 'twin-turbo'],
                    ['label' => 'Supercharged', 'value' => 'supercharged'],
                ],
            ],
            [
                'name' => 'Top Speed',
                'slug' => 'top-speed',
                'attribute_group_id' => $engineGroup->id,
                'type' => 'number',
                'suffix' => 'km/h',
                'show_in_details' => true,
                'placeholder' => 'e.g., 250',
            ],
            [
                'name' => '0-100 km/h',
                'slug' => 'acceleration',
                'attribute_group_id' => $engineGroup->id,
                'type' => 'number',
                'suffix' => 'sec',
                'show_in_card' => true,
                'show_in_details' => true,
                'placeholder' => 'e.g., 4.5',
            ],

            // Exterior
            [
                'name' => 'Sunroof/Moonroof',
                'slug' => 'sunroof',
                'attribute_group_id' => $exteriorGroup->id,
                'type' => 'select',
                'show_in_filters' => true,
                'show_in_details' => true,
                'options' => [
                    ['label' => 'None', 'value' => 'none'],
                    ['label' => 'Sunroof', 'value' => 'sunroof'],
                    ['label' => 'Moonroof', 'value' => 'moonroof'],
                    ['label' => 'Panoramic', 'value' => 'panoramic'],
                ],
            ],
            [
                'name' => 'Wheel Size',
                'slug' => 'wheel-size',
                'attribute_group_id' => $exteriorGroup->id,
                'type' => 'select',
                'show_in_filters' => true,
                'show_in_details' => true,
                'options' => [
                    ['label' => '16 inch', 'value' => '16'],
                    ['label' => '17 inch', 'value' => '17'],
                    ['label' => '18 inch', 'value' => '18'],
                    ['label' => '19 inch', 'value' => '19'],
                    ['label' => '20 inch', 'value' => '20'],
                    ['label' => '21+ inch', 'value' => '21+'],
                ],
            ],

            // Interior
            [
                'name' => 'Leather Seats',
                'slug' => 'leather-seats',
                'attribute_group_id' => $interiorGroup->id,
                'type' => 'boolean',
                'show_in_filters' => true,
                'show_in_details' => true,
            ],
            [
                'name' => 'Heated Seats',
                'slug' => 'heated-seats',
                'attribute_group_id' => $interiorGroup->id,
                'type' => 'boolean',
                'show_in_filters' => true,
                'show_in_details' => true,
            ],
            [
                'name' => 'Ventilated Seats',
                'slug' => 'ventilated-seats',
                'attribute_group_id' => $interiorGroup->id,
                'type' => 'boolean',
                'show_in_details' => true,
            ],

            // Safety
            [
                'name' => 'ABS',
                'slug' => 'abs',
                'attribute_group_id' => $safetyGroup->id,
                'type' => 'boolean',
                'show_in_details' => true,
            ],
            [
                'name' => 'Airbags',
                'slug' => 'airbags',
                'attribute_group_id' => $safetyGroup->id,
                'type' => 'select',
                'show_in_filters' => true,
                'show_in_details' => true,
                'options' => [
                    ['label' => '2 Airbags', 'value' => '2'],
                    ['label' => '4 Airbags', 'value' => '4'],
                    ['label' => '6 Airbags', 'value' => '6'],
                    ['label' => '8+ Airbags', 'value' => '8+'],
                ],
            ],
            [
                'name' => 'Blind Spot Monitor',
                'slug' => 'blind-spot-monitor',
                'attribute_group_id' => $safetyGroup->id,
                'type' => 'boolean',
                'show_in_filters' => true,
                'show_in_details' => true,
            ],

            // Technology
            [
                'name' => 'Navigation System',
                'slug' => 'navigation',
                'attribute_group_id' => $techGroup->id,
                'type' => 'boolean',
                'show_in_filters' => true,
                'show_in_details' => true,
            ],
            [
                'name' => 'Apple CarPlay/Android Auto',
                'slug' => 'carplay-android-auto',
                'attribute_group_id' => $techGroup->id,
                'type' => 'boolean',
                'show_in_filters' => true,
                'show_in_details' => true,
            ],
            [
                'name' => 'Parking Sensors',
                'slug' => 'parking-sensors',
                'attribute_group_id' => $techGroup->id,
                'type' => 'select',
                'show_in_details' => true,
                'options' => [
                    ['label' => 'None', 'value' => 'none'],
                    ['label' => 'Rear Only', 'value' => 'rear'],
                    ['label' => 'Front & Rear', 'value' => 'front-rear'],
                    ['label' => '360° Camera', 'value' => '360'],
                ],
            ],
        ];

        // Create attributes with options
        foreach ($attributes as $attrData) {
            $options = $attrData['options'] ?? [];
            unset($attrData['options']);

            $attribute = Attribute::create($attrData);

            // Create options
            foreach ($options as $index => $option) {
                $attribute->options()->create([
                    'label' => $option['label'],
                    'value' => $option['value'],
                    'order' => $index,
                ]);
            }
        }

        // Assign all attributes to all categories
        $allCategories = Category::all();
        $allAttributes = Attribute::all();
        foreach ($allCategories as $category) {
            foreach ($allAttributes as $attribute) {
                $category->attributes()->attach($attribute->id, ['order' => $attribute->order ?? 0]);
            }
        }
    }
}
