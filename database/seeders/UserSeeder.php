<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@xenonmotors.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone' => '+971501234567',
            'whatsapp' => '+971501234567',
            'city' => 'Dubai',
            'country' => 'UAE',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@xenonmotors.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone' => '+971509876543',
            'whatsapp' => '+971509876543',
            'city' => 'Abu Dhabi',
            'country' => 'UAE',
            'is_active' => true,
        ]);
        $user->assignRole('user');
    }
}
