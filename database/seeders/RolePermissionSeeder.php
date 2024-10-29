<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the permissions for each role
        $rolePermissions = [
            'admin' => ['create-asset', 
                        'edit-asset', 
                        'delete-asset', 
                        'update-asset', 
                        'access-asset', 
                        'view-asset',
                        'view-dashboard',
                        'create-department',
                        'access-department',
                        'view-department',
                        'edit-department',
                        'update-department',
                        'delete-department',
                        'view-location',
                        'access-location',
                        'create-location',
                        'edit-location',
                        'update-location',
                        'delete-location',
                        'create-region',
                        'view-region',
                        'access-region',
                        'edit-region',
                        'update-region',
                        'delete-region',
                        'view-vendor',
                        'access-vendor',
                        'create-vendor',
                        'edit-vendor',
                        'update-vendor',
                        'delete-vendor',
                        'create-asset_category',
                        'access-asset_category',
                        'view-asset_category',
                        'delete-asset_category',
                        'edit-asset_category',
                        'update-asset_category',
                        'view-report',
                        'view-insurance_report',
                        'view-insurance',
                        'create-insurance',
                        'delete-insurance',
                        'edit-insurance',
                        'update-insurance',
                        'access-insurance',
                        'view-invoice',
                        'create-invoice',
                        'edit-invoice',
                        'update-invoice',
                        'delete-invoice',
                        'access-invoice',
                        'view-setting',
                        'update-setting',
                        'view-user',
                        'update-user',
                        'create-user',
                        'edit-user',
                        'delete-user',
                        'access-user',
                        'delete-role',
                        'create-role',
                        'edit-role',
                        'access-role',
                        'delete-permission',
                        'create-permission',
                        'access-permission',
                        'edit-permission',
                        'update-permission',
                        'view-assetHistory',
                        'access-assetHistory',
                        'delete-assetHistory',
                    ],

            'technician' => ['create-asset',
                             'edit-asset', 
                             'update-asset', 
                             'view-asset',                     
                             'access-asset',
                             'view-dashboard',
                             'view-location',
                             'create-location',
                             'edit-location',
                             'update-location',
                             'access-location',
                             'view-region',
                             'edit-region',
                             'create-region',
                             'update-region',
                             'access-region',
                             'view-department',
                             'create-department',
                             'edit-department',
                             'update-department',
                             'view-vendor',
                             'create-vendor',
                             'edit-vendor',
                             'update-vendor',
                             'access-vendor',
                             'view-asset_category',
                             'create-asset_category',
                             'edit-asset_category',
                             'update-asset_category',
                             'access-asset_category',
                             'view-report',
                             'access-report',
                             'view-insurance',
                             'create-insurance',
                             'edit-insurance',
                             'update-insurance',
                             'access-insurance',
                             'view-invoice',
                             'create-invoice',
                             'edit-invoice',
                             'update-invoice',
                             'access-invoice',
                             'view-assetHistory',
                             'access-assetHistory',
                             'access-report',
                             'access-department',
                            ],

            'viewer' => ['view-dashboard'],

            'costing_department' => ['view-dashboard',
                                     'view-report',
                                     'view-setting',
                                     'update-setting',
                                     'access-report',
                                     'restore-asset',
                                     'view-asset',
                                     'create-asset',
                                     'access-asset',
                                     'edit-asset',
                                     'delete-asset',
                                     'create-asset',
                                     'view-assetHistory',
                                    'access-assetHistory',
                                    ],

            'normal_user' => ['view-dashboard',
                            //    'create-asset',
                            //    'access-asset',
                            //    'edit-asset',
                               'view-asset',
                               'view-report',
                               'access-report',
                            //    'view-invoice',
                            //    'create-invoice',
                            //    'edit-invoice',
                            //    'access-invoice',
                               'view-assetHistory',
                               
                            ],
        ];

        // New permissions to be added
        $newPermissions = [
            'view-assetHistory',
            'delete-assetHistory',
            'access-assetHistory',
            'access-asset',
            'access-department',
            'access-asset_category',
            'access-vendor',
            'access-region',
            'access-invoice',
            'access-insurance',
            'access-report',
            'access-user',
            'access-role',
            'access-permission',
        ];

        // Retrieve existing permissions from the database
        $existingPermissions = Permission::pluck('name')->toArray();

        // Combine existing and new permissions
        $permissions = array_merge($existingPermissions, $newPermissions);

        // Seed permissions into the database
        foreach ($rolePermissions as $roleName => $permissions) {
            foreach ($permissions as $permissionName) {
                Permission::firstOrCreate(['name' => $permissionName]);
            }
        }

        // Ensure the roles exist before attaching permissions
        foreach (array_keys($rolePermissions) as $roleName) {
            Role::firstOrCreate(['role_name' => $roleName]);
        }

        // Attach permissions to roles
        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('role_name', $roleName)->first();
            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                $role->permissions()->attach($permission);
            }
        }
    

        
    }
}
