<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;
use Illuminate\Support\Str;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            Stock::create([
                'mark_no'   => 'M' . str_pad($i, 3, '0', STR_PAD_LEFT), // M001, M002,...
                'design_no' => 'D' . str_pad(rand(1, 1000), 4, '0', STR_PAD_LEFT),
                'item_name' => fake()->randomElement(['Cotton Shirt', 'Denim Jeans', 'Linen Kurta', 'Silk Saree']),
                'quantity'  => fake()->numberBetween(2,500), // 1.00 to 500.00
                'message'   => fake()->sentence(),
                'status'    => fake()->randomElement([0, 1]),
            ]);
        }
    }
}
