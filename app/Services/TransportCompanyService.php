<?php

namespace App\Services;

use App\Models\TransportCompany;

class TransportCompanyService
{
    /**
     * Get all active transport companies.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCompanies()
    {
        return TransportCompany::where('status', 1)->get();
    }

    public function createAndFind($name): int
    {
        $existing = TransportCompany::where('id', $name)->first();

        if ($existing) {
            return $existing->id;
        }

        $newTransportCompany = TransportCompany::create(['name' => $name]);
        return $newTransportCompany->id;
    }
}
