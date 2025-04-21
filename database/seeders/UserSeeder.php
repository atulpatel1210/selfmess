<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $secretaryRole = Role::where('name', 'secretary')->first();
        $staffRole = Role::where('name', 'staff')->first();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'mobile' => '1234567890',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Secretary User',
            'email' => 'secretary@example.com',
            'mobile' => '1234567891',
            'password' => Hash::make('password'),
            'role_id' => $secretaryRole->id,
        ]);

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'mobile' => '1234567892',
            'password' => Hash::make('password'),
            'role_id' => $staffRole->id,
        ]);
    }
}