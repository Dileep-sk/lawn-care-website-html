<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DesignNo;

class DesignNoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefixes = ['D1', 'D2', 'D3', 'D4', 'D5']; // you can expand prefixes
        $statusOptions = [0, 1];

        for ($i = 1; $i <= 100; $i++) {
            $prefix = $prefixes[array_rand($prefixes)];
            $number = str_pad($i, 4, '0', STR_PAD_LEFT); // e.g., D10001
            DesignNo::create([
                'name' => $prefix . $number,
                'status' => $statusOptions[array_rand($statusOptions)]
            ]);
        }
    }
}
