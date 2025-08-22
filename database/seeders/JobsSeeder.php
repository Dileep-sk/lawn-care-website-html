<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class JobsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();


        for ($i = 0; $i < 500; $i++) {
            DB::table('order_jobs')->insert([
                'company_name' => $faker->company,
                'design_no' => strtoupper($faker->bothify('D-###??')),
                'image' => $faker->optional()->imageUrl(640, 480, 'business'),
                'quantity' => $faker->numberBetween(1, 1000),
                'order_no' => strtoupper($faker->bothify('ORD-#####')),
                'status' => $faker->numberBetween(0, 3), // Example status codes 0-3
                'message' => $faker->sentence(),
                'created_at' => now(),
                'updated_at' => now(),
                // matching_1 to matching_8
                'matching_1' => $faker->optional()->word,
                'matching_2' => $faker->optional()->word,
                'matching_3' => $faker->optional()->word,
                'matching_4' => $faker->optional()->word,
                'matching_5' => $faker->optional()->word,
                'matching_6' => $faker->optional()->word,
                'matching_7' => $faker->optional()->word,
                'matching_8' => $faker->optional()->word,
            ]);
        }
    }
}
