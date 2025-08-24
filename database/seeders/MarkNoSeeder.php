<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarkNoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusOptions = [0, 1];
        $data = [];

        for ($i = 1; $i <= 100; $i++) {
            $data[] = [
                'name' => 'Mark No ' . $i,
                'status' => $statusOptions[array_rand($statusOptions)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('mark_nos')->insert($data);
    }
}
