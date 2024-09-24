<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'password' => bcrypt('123456'),
            'role_id' => '1',
            'merchant_id' => '1',
            'status' => '1',
            // Hash the password using bcrypt
        ]);
    }
}
