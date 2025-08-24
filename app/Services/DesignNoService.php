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

    public function createAndFind($designNo)
    {
        $isExit = DesignNo::find($designNo);

        if (isset($isExit)) {
            return $isExit;
        } else {
            $newDesignNo = DesignNo::create(['name' => $designNo]);
            return $newDesignNo->id;
        }
    }
}
