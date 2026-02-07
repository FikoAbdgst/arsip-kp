<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User yang sudah ada
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bri.local',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@bri.local',
            'password' => bcrypt('password'),
            'role' => 'supervisor',
        ]);

        // Panggil DocumentSeeder
        $this->call(DocumentSeeder::class);
    }
}
