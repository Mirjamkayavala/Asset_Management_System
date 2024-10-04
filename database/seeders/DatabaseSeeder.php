<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
           
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            // UserRoleSeeder::class,
            // AdminSeeder::class,
            // RoleSeeder::class,
          
        ]);

        User::factory()->create([
            'username' => 'Admin',
            'role_id' => '1',
            'name' => 'Admin User',
            'email' => 'admin@cenored.com.na',
            
            // 'role' => 'admin',
            'contact_number' => '06752564', // Add this line
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Use bcrypt for hashing the password
            'remember_token' => \Str::random(10)
        ]);
    }
}
