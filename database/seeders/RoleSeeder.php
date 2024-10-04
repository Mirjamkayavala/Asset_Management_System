<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRoles = [
            1 => 'admin', // User ID 1 assigned to admin role
            2 => 'technician', // User ID 2 assigned to manager role
            3 => 'viewer', // User ID 3 assigned to viewer role
            5 => 'normal_user', // User ID 3 assigned to viewer role
            4 => 'costing_department', // User ID 3 assigned to viewer role
        ];

        foreach ($userRoles as $userId => $roleName) {
            $user = User::find($userId);
            $role = Role::where('name', $roleName)->first();
            $user->roles()->attach($role);
        }
    }
}
