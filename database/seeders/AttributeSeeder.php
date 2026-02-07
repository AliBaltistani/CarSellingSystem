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
            ['name' => 'Engine & Performance', 'slug' => 'engine-performance', 'icon' => '⚙️', 'order' => 1],
            ['name' => 'Exterior', 'slug' => 'exterior', 'icon' => '🚗', 'order' => 2],
            ['name' => 'Interior & Comfort', 'slug' => 'interior-comfort', 'icon' => '🪑', 'order' => 3],
            ['name' => 'Safety & Security', 'slug' => 'safety-security', 'icon' => '🛡️', 'order' => 4],
            ['name' => 'Technology', 'slug' => 'technology', 'icon' => '📱', 'order' => 5],
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

        // Assign all attributes to main categories
        $carCategory = Category::where('slug', 'cars')->orWhere('name', 'like', '%car%')->first();
        if ($carCategory) {
            $allAttributes = Attribute::all();
            foreach ($allAttributes as $attribute) {
                $attribute->categories()->attach($carCategory->id, ['order' => $attribute->order]);
            }
        }
    }
}
