<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin role
        Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Administrator with full access',
            'permissions' => [
                'manage_users', 'manage_roles', 'manage_contacts', 
                'manage_companies', 'manage_deals', 'manage_tasks'
            ]
        ]);
        
        // Manager role
        Role::create([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Manager with limited access',
            'permissions' => [
                'view_users', 'manage_contacts', 'manage_companies', 
                'manage_deals', 'manage_tasks'
            ]
        ]);
        
        // User role
        Role::create([
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Regular user',
            'permissions' => [
                'view_contacts', 'view_companies', 'view_deals',
                'manage_own_tasks'
            ]
        ]);
    }
}
