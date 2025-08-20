<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Order::create([
                'customer_name'      => $faker->name,
                'order_no'           => 'ORD' . str_pad($index, 4, '0', STR_PAD_LEFT), // e.g., ORD0001
                'date'               => $faker->date(),
                'broker_name'        => $faker->name,
                'transport_company'  => $faker->company,
                'design_no'          => 'D' . $faker->numberBetween(1000, 9999),
                'item_name'          => $faker->word . ' Shirt',
                'quantity' => $faker->numberBetween(1, 1000),
                'rate'               => $faker->randomFloat(2, 10, 500),
                'status'             => $faker->boolean ? 1 : 0,
                'message'            => $faker->sentence,
            ]);
        }
    }
}
