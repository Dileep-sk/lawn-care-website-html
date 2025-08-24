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
        $existing = Item::where('id', $name)->first();

        if ($existing) {
            return $existing->id;
        }

        $newItem = Item::create(['name' => $name]);
        return $newItem->id;
    }
}
