<?php

namespace Database\Seeders;

use App\Models\DropdownOption;
use Illuminate\Database\Seeder;

class DropdownOptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            // Car Makes
            DropdownOption::TYPE_MAKE => [
                ['label' => 'Toyota', 'order' => 1],
                ['label' => 'Honda', 'order' => 2],
                ['label' => 'BMW', 'order' => 3],
                ['label' => 'Mercedes-Benz', 'slug' => 'mercedes-benz', 'order' => 4],
                ['label' => 'Nissan', 'order' => 5],
                ['label' => 'Ford', 'order' => 6],
                ['label' => 'Chevrolet', 'order' => 7],
                ['label' => 'Lexus', 'order' => 8],
                ['label' => 'Porsche', 'order' => 9],
                ['label' => 'Audi', 'order' => 10],
                ['label' => 'Volkswagen', 'order' => 11],
                ['label' => 'Hyundai', 'order' => 12],
                ['label' => 'Kia', 'order' => 13],
                ['label' => 'Mazda', 'order' => 14],
                ['label' => 'Subaru', 'order' => 15],
                ['label' => 'Jeep', 'order' => 16],
                ['label' => 'Land Rover', 'slug' => 'land-rover', 'order' => 17],
                ['label' => 'Range Rover', 'slug' => 'range-rover', 'order' => 18],
                ['label' => 'Jaguar', 'order' => 19],
                ['label' => 'Ferrari', 'order' => 20],
                ['label' => 'Lamborghini', 'order' => 21],
                ['label' => 'Bentley', 'order' => 22],
                ['label' => 'Rolls-Royce', 'slug' => 'rolls-royce', 'order' => 23],
                ['label' => 'Maserati', 'order' => 24],
                ['label' => 'Aston Martin', 'slug' => 'aston-martin', 'order' => 25],
                ['label' => 'Tesla', 'order' => 26],
                ['label' => 'Volvo', 'order' => 27],
                ['label' => 'Infiniti', 'order' => 28],
                ['label' => 'Acura', 'order' => 29],
                ['label' => 'GMC', 'order' => 30],
                ['label' => 'Cadillac', 'order' => 31],
                ['label' => 'Dodge', 'order' => 32],
                ['label' => 'Chrysler', 'order' => 33],
                ['label' => 'Mitsubishi', 'order' => 34],
                ['label' => 'Suzuki', 'order' => 35],
                ['label' => 'Peugeot', 'order' => 36],
                ['label' => 'Renault', 'order' => 37],
                ['label' => 'Fiat', 'order' => 38],
                ['label' => 'Mini', 'order' => 39],
                ['label' => 'Genesis', 'order' => 40],
                ['label' => 'Lincoln', 'order' => 41],
                ['label' => 'Buick', 'order' => 42],
                ['label' => 'Other', 'order' => 99],
            ],

            // Vehicle Condition
            DropdownOption::TYPE_CONDITION => [
                ['label' => 'New', 'value' => 'new', 'order' => 1],
                ['label' => 'Used', 'value' => 'used', 'order' => 2],
                ['label' => 'Certified Pre-Owned', 'value' => 'certified', 'order' => 3],
            ],

            // Transmission Types
            DropdownOption::TYPE_TRANSMISSION => [
                ['label' => 'Automatic', 'value' => 'automatic', 'order' => 1],
                ['label' => 'Manual', 'value' => 'manual', 'order' => 2],
                ['label' => 'CVT', 'value' => 'cvt', 'order' => 3],
                ['label' => 'Semi-Automatic', 'value' => 'semi-automatic', 'order' => 4],
                ['label' => 'Dual-Clutch (DCT)', 'value' => 'dct', 'order' => 5],
            ],

            // Fuel Types
            DropdownOption::TYPE_FUEL_TYPE => [
                ['label' => 'Petrol', 'value' => 'petrol', 'order' => 1],
                ['label' => 'Diesel', 'value' => 'diesel', 'order' => 2],
                ['label' => 'Electric', 'value' => 'electric', 'order' => 3],
                ['label' => 'Hybrid', 'value' => 'hybrid', 'order' => 4],
                ['label' => 'Plug-in Hybrid', 'value' => 'plugin-hybrid', 'order' => 5],
                ['label' => 'CNG', 'value' => 'cng', 'order' => 6],
                ['label' => 'LPG', 'value' => 'lpg', 'order' => 7],
            ],

            // Body Types
            DropdownOption::TYPE_BODY_TYPE => [
                ['label' => 'Sedan', 'value' => 'sedan', 'order' => 1],
                ['label' => 'SUV', 'value' => 'suv', 'order' => 2],
                ['label' => 'Coupe', 'value' => 'coupe', 'order' => 3],
                ['label' => 'Hatchback', 'value' => 'hatchback', 'order' => 4],
                ['label' => 'Convertible', 'value' => 'convertible', 'order' => 5],
                ['label' => 'Wagon', 'value' => 'wagon', 'order' => 6],
                ['label' => 'Pickup Truck', 'value' => 'pickup', 'order' => 7],
                ['label' => 'Van', 'value' => 'van', 'order' => 8],
                ['label' => 'Minivan', 'value' => 'minivan', 'order' => 9],
                ['label' => 'Crossover', 'value' => 'crossover', 'order' => 10],
                ['label' => 'Sports Car', 'value' => 'sports', 'order' => 11],
                ['label' => 'Luxury', 'value' => 'luxury', 'order' => 12],
            ],

            // Drivetrain
            DropdownOption::TYPE_DRIVETRAIN => [
                ['label' => 'Front-Wheel Drive (FWD)', 'value' => 'fwd', 'order' => 1],
                ['label' => 'Rear-Wheel Drive (RWD)', 'value' => 'rwd', 'order' => 2],
                ['label' => 'All-Wheel Drive (AWD)', 'value' => 'awd', 'order' => 3],
                ['label' => 'Four-Wheel Drive (4WD)', 'value' => '4wd', 'order' => 4],
            ],

            // Exterior Colors
            DropdownOption::TYPE_EXTERIOR_COLOR => [
                ['label' => 'White', 'value' => 'white', 'color' => '#FFFFFF', 'order' => 1],
                ['label' => 'Black', 'value' => 'black', 'color' => '#000000', 'order' => 2],
                ['label' => 'Silver', 'value' => 'silver', 'color' => '#C0C0C0', 'order' => 3],
                ['label' => 'Gray', 'value' => 'gray', 'color' => '#808080', 'order' => 4],
                ['label' => 'Red', 'value' => 'red', 'color' => '#FF0000', 'order' => 5],
                ['label' => 'Blue', 'value' => 'blue', 'color' => '#0066CC', 'order' => 6],
                ['label' => 'Navy Blue', 'value' => 'navy', 'color' => '#000080', 'order' => 7],
                ['label' => 'Green', 'value' => 'green', 'color' => '#008000', 'order' => 8],
                ['label' => 'Brown', 'value' => 'brown', 'color' => '#8B4513', 'order' => 9],
                ['label' => 'Beige', 'value' => 'beige', 'color' => '#F5F5DC', 'order' => 10],
                ['label' => 'Gold', 'value' => 'gold', 'color' => '#FFD700', 'order' => 11],
                ['label' => 'Orange', 'value' => 'orange', 'color' => '#FFA500', 'order' => 12],
                ['label' => 'Yellow', 'value' => 'yellow', 'color' => '#FFFF00', 'order' => 13],
                ['label' => 'Purple', 'value' => 'purple', 'color' => '#800080', 'order' => 14],
                ['label' => 'Burgundy', 'value' => 'burgundy', 'color' => '#800020', 'order' => 15],
                ['label' => 'Champagne', 'value' => 'champagne', 'color' => '#F7E7CE', 'order' => 16],
                ['label' => 'Pearl White', 'value' => 'pearl-white', 'color' => '#FAFAFA', 'order' => 17],
                ['label' => 'Midnight Black', 'value' => 'midnight-black', 'color' => '#1C1C1C', 'order' => 18],
                ['label' => 'Other', 'value' => 'other', 'order' => 99],
            ],

            // Interior Colors
            DropdownOption::TYPE_INTERIOR_COLOR => [
                ['label' => 'Black', 'value' => 'black', 'color' => '#000000', 'order' => 1],
                ['label' => 'Beige', 'value' => 'beige', 'color' => '#F5F5DC', 'order' => 2],
                ['label' => 'Brown', 'value' => 'brown', 'color' => '#8B4513', 'order' => 3],
                ['label' => 'Tan', 'value' => 'tan', 'color' => '#D2B48C', 'order' => 4],
                ['label' => 'Gray', 'value' => 'gray', 'color' => '#808080', 'order' => 5],
                ['label' => 'White', 'value' => 'white', 'color' => '#FFFFFF', 'order' => 6],
                ['label' => 'Red', 'value' => 'red', 'color' => '#8B0000', 'order' => 7],
                ['label' => 'Cream', 'value' => 'cream', 'color' => '#FFFDD0', 'order' => 8],
                ['label' => 'Two-Tone', 'value' => 'two-tone', 'order' => 9],
                ['label' => 'Other', 'value' => 'other', 'order' => 99],
            ],

            // Number of Doors
            DropdownOption::TYPE_DOORS => [
                ['label' => '2 Doors', 'value' => '2', 'order' => 1],
                ['label' => '3 Doors', 'value' => '3', 'order' => 2],
                ['label' => '4 Doors', 'value' => '4', 'order' => 3],
                ['label' => '5 Doors', 'value' => '5', 'order' => 4],
            ],

            // Number of Seats
            DropdownOption::TYPE_SEATS => [
                ['label' => '2 Seats', 'value' => '2', 'order' => 1],
                ['label' => '4 Seats', 'value' => '4', 'order' => 2],
                ['label' => '5 Seats', 'value' => '5', 'order' => 3],
                ['label' => '6 Seats', 'value' => '6', 'order' => 4],
                ['label' => '7 Seats', 'value' => '7', 'order' => 5],
                ['label' => '8+ Seats', 'value' => '8+', 'order' => 6],
            ],

            // Number of Cylinders
            DropdownOption::TYPE_CYLINDERS => [
                ['label' => '3 Cylinders', 'value' => '3', 'order' => 1],
                ['label' => '4 Cylinders', 'value' => '4', 'order' => 2],
                ['label' => '5 Cylinders', 'value' => '5', 'order' => 3],
                ['label' => '6 Cylinders', 'value' => '6', 'order' => 4],
                ['label' => '8 Cylinders', 'value' => '8', 'order' => 5],
                ['label' => '10 Cylinders', 'value' => '10', 'order' => 6],
                ['label' => '12 Cylinders', 'value' => '12', 'order' => 7],
                ['label' => 'Electric (N/A)', 'value' => 'electric', 'order' => 8],
            ],
        ];

        foreach ($options as $type => $items) {
            foreach ($items as $item) {
                DropdownOption::updateOrCreate(
                    ['type' => $type, 'slug' => $item['slug'] ?? \Illuminate\Support\Str::slug($item['label'])],
                    array_merge(['type' => $type], $item)
                );
            }
        }
    }
}
