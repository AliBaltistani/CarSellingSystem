<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            CategorySeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            DropdownOptionSeeder::class, // Added this line
            CarSeeder::class,
            TestimonialSeeder::class,
            FinancingPartnerSeeder::class,
            OfferSeeder::class,
            AttributeSeeder::class,
            PageSeeder::class,
        ]);
    }
}

