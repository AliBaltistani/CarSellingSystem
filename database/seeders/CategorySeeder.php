<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sedan',
                'slug' => 'sedan',
                'description' => 'Comfortable 4-door passenger cars with enclosed trunk',
                'icon' => 'sedan',
                'order' => 1,
            ],
            [
                'name' => 'SUV',
                'slug' => 'suv',
                'description' => 'Sport Utility Vehicles - spacious and versatile',
                'icon' => 'suv',
                'order' => 2,
            ],
            [
                'name' => 'Coupe',
                'slug' => 'coupe',
                'description' => 'Sporty 2-door cars with sleek design',
                'icon' => 'coupe',
                'order' => 3,
            ],
            [
                'name' => 'Hatchback',
                'slug' => 'hatchback',
                'description' => 'Compact cars with rear door for easy access',
                'icon' => 'hatchback',
                'order' => 4,
            ],
            [
                'name' => 'Pickup Truck',
                'slug' => 'pickup-truck',
                'description' => 'Trucks with open cargo area for hauling',
                'icon' => 'pickup',
                'order' => 5,
            ],
            [
                'name' => 'Van',
                'slug' => 'van',
                'description' => 'Multi-passenger vehicles for families',
                'icon' => 'van',
                'order' => 6,
            ],
            [
                'name' => 'Convertible',
                'slug' => 'convertible',
                'description' => 'Cars with retractable roof for open-air driving',
                'icon' => 'convertible',
                'order' => 7,
            ],
            [
                'name' => 'Sports Car',
                'slug' => 'sports-car',
                'description' => 'High-performance vehicles for enthusiasts',
                'icon' => 'sports',
                'order' => 8,
            ],
            [
                'name' => 'Luxury',
                'slug' => 'luxury',
                'description' => 'Premium luxury vehicles with top amenities',
                'icon' => 'luxury',
                'order' => 9,
            ],
            [
                'name' => 'Electric',
                'slug' => 'electric',
                'description' => 'Electric and hybrid eco-friendly vehicles',
                'icon' => 'electric',
                'order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
