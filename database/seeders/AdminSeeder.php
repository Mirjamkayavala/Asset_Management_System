<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the role 'admin' exists
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            // Optionally, you could create the admin role here if not exists
            $adminRole = Role::create(['name' => 'admin']);
        }

        // Create the admin user if it doesn't exist
        $adminUser = User::firstOrCreate(
            [
                'email' => 'mirjamkayavala@gmail.com', // Change this to the admin email you want
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'), // Change this to a secure password
                'contact_number' => '1234567890', // Set an appropriate contact number
                'department_id' => 18, // Set department ID (if applicable)
            ]
        );

        // Assign the admin role to the user
        $adminUser->assignRole($adminRole);
    }
}
