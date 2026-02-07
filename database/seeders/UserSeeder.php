<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bri.local'],
            [
                'name' => 'Admin Arsip',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'supervisor@bri.local'],
            [
                'name' => 'Supervisor Arsip',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'teller@bri.local'],
            [
                'name' => 'Admin Teller',
                'password' => Hash::make('password'),
                'role' => 'teller',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cs@bri.local'],
            [
                'name' => 'Admin Customer Service',
                'password' => Hash::make('password'),
                'role' => 'cs',
            ]
        );
    }
}
