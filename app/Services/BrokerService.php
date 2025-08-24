<?php

namespace App\Services;

use App\Models\Broker;

class BrokerService
{
    /**
     * Get all active brokers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveBrokers()
    {
        return Broker::where('status', 1)->get();
    }

    public function createAndFind($name): int
    {
        $existing = Broker::where('id', $name)->first();

        if ($existing) {
            return $existing->id;
        }

        $newBroker = Broker::create(['name' => $name]);
        return $newBroker->id;
    }
}
