<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Car permissions
            'view cars',
            'create cars',
            'edit cars',
            'delete cars',
            'feature cars',
            'publish cars',
            
            // Category permissions
            'manage categories',
            
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            'ban users',
            
            // Inquiry permissions
            'view inquiries',
            'manage inquiries',
            
            // Settings permissions
            'manage settings',
            
            // Reports permissions
            'view reports',
            'export reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create admin role and assign all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create user role with limited permissions
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view cars',
            'create cars',
            'edit cars',
            'delete cars',
        ]);
    }
}
