<?php

namespace App\Services;

use App\Models\DesignNo;

class DesignNoService
{
    public function getAll()
    {
        return DesignNo::where('status', 1)->get();

        return $query;
    }

    public function createAndFind($name): int
    {

        if (is_numeric($name)) {
            $existing = DesignNo::find($name);

            if ($existing) {
                return $existing->id;
            }
        }

        $existing = DesignNo::where('name', $name)->first();

        if ($existing) {
            return $existing->id;
        }

        $new = DesignNo::create(['name' => $name]);

        return $new->id;
    }
}
