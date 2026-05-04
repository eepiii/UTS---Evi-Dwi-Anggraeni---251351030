<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 2 Role
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $staffRole   = Role::firstOrCreate(['name' => 'staff']);

        // User Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name'     => 'Manager User',
                'password' => Hash::make('password'),
            ]
        );
        $manager->assignRole($managerRole);

        // User Staff
        $staff = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name'     => 'Staff User',
                'password' => Hash::make('password'),
            ]
        );
        $staff->assignRole($staffRole);
    }
}