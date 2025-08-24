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

    public function createAndFind($item_name)
    {
        $isExit = Item::find($item_name);

        if (isset($isExit)) {
            return $isExit;
        } else {
            $newItem = Item::create(['name' => $item_name]);
            return $newItem->id;
        }
    }
}
