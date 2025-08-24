<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemTypes = ['Shirt', 'Jeans', 'Jacket', 'Sweater', 'Scarf', 'Coat', 'T-Shirt', 'Hoodie', 'Blouse', 'Skirt'];
        $materials = ['Cotton', 'Denim', 'Leather', 'Wool', 'Silk', 'Polyester', 'Linen', 'Velvet'];

        $statusOptions = [0, 1];

        for ($i = 1; $i <= 100; $i++) {
            $material = $materials[array_rand($materials)];
            $type = $itemTypes[array_rand($itemTypes)];
            $name = $material . ' ' . $type . ' #' . $i;

            Item::create([
                'name' => $name,
                'status' => $statusOptions[array_rand($statusOptions)]
            ]);
        }
    }
}
