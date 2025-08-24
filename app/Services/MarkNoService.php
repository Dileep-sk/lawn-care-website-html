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

     public function createAndFind($mark_no)
    {
        $isExit = MarkNo::find($mark_no);

        if (isset($isExit)) {
            return $isExit;
        } else {
            $newMarkNo = MarkNo::create(['name' => $mark_no]);
            return $newMarkNo->id;
        }
    }
}
