<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => "Admin",
            'email' => "admin@gmail.com",
            'mobile_number' => "123456789",
            'password' => Hash::make('admin@gmail.com'),
            'status' => rand(0, 1),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Call other seeders
        // $this->call([
        //     UserSeeder::class,
        //     StockSeeder::class,
        //     OrderSeeder::class,
        //     JobsSeeder::class,
        // ]);
    }
}
