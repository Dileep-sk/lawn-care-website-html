<?php

namespace App\Services;

use App\Models\MarkNo;

class MarkNoService
{
    /**
     * Get all mark numbers
     */
    public function getAll()
    {
        return MarkNo::all();
    }

    /**
     * Get only active mark numbers
     */
    public function getActive()
    {
        return MarkNo::where('status', 1)->get();
    }

    public function createAndFind($name): int
    {
        $existing = MarkNo::where('id', $name)->first();

        if ($existing) {
            return $existing->id;
        }

        $new = MarkNo::create(['name' => $name]);
        return $new->id;
    }
}
