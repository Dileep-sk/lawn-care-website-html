<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    /**
     * Get all active customers (status = 1)
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCustomers()
    {
        return Customer::where('status', 1)->get();
    }

    public function createAndFind($name): int
    {
        if (is_numeric($name)) {
            $existing = Customer::find($name);

            if ($existing) {
                return $existing->id;
            }
        }

        $existing = Customer::where('name', $name)->first();

        if ($existing) {
            return $existing->id;
        }

        $newCustomer = Customer::create(['name' => $name]);

        return $newCustomer->id;
    }
}
