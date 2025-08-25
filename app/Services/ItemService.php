<?php

namespace App\Services;

use App\Models\Item;

use function PHPUnit\Framework\isString;

class ItemService
{
    public function getAll($onlyActive = false)
    {
        $query = Item::query();

        if ($onlyActive) {
            $query->where('status', 1);
        }

        return $query->select('id', 'name')->orderBy('name')->get();
    }

    public function createAndFind($name): int
    {
        if (is_numeric($name)) {
            $existing = Item::find($name);

            if ($existing) {
                return $existing->id;
            }
        }

        $existing = Item::where('name', $name)->first();

        if ($existing) {
            return $existing->id;
        }
        $newItem = Item::create(['name' => $name]);
        return $newItem->id;
    }
}
