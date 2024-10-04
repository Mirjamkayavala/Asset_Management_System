<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'create-asset'],
            ['name' => 'edit-asset'],
            ['name' => 'delete-asset'],
            ['name' => 'view-asset'],
            ['name' => 'view-department'],
            ['name' => 'create-department'],
            ['name' => 'delete-department'],
            ['name' => 'edit-department'],
            ['name' => 'view-dashboard'],
            ['name' => 'view-location'],
            ['name' => 'create-location'],
            ['name' => 'edit-location'],
            ['name' => 'delete-location'],
            ['name' => 'view-region'],
            ['name' => 'create-region'],
            ['name' => 'edit-region'],
            ['name' => 'delete-region'],
            ['name' => 'view-vendor'],
            ['name' => 'create-vendor'],
            ['name' => 'delete-vendor'],
            ['name' => 'edit-vendor'],
            ['name' => 'view-asset_category'],
            ['name' => 'delete-asset_category'],
            ['name' => 'edit-asset_category'],
            ['name' => 'create-asset_category'],
            ['name' => 'view-assetHistory'],
            
            ['name' => 'view-report'],
            // Add more permissions as needed
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
