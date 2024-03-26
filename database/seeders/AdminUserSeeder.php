<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
           $adminUsers = User::whereHas('role', function($q) {
                $q->where('name', '=', 'SUPER_ADMIN');
            })->get();

            $adminUsers->each->forceDelete();        

               // Add a new admin user
              User::insert([
                   'first_name' => 'Admin',
                   'last_name' => 'User',
                   'email' => 'admin@gmail.com',
                   'phone' => 9087654321,
                   'role_id' => 1,
                   'password' => Hash::make('password'),
                   'status' => 'active',
                   'email_verified_at' => now(),
               ]);

               $this->command->info('Admin user created successfully.');
    }
}
