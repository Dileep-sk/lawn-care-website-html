<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Get active customers (status = 1)
     */
    public function index()
    {
        $customers = Customer::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $customers
        ], 200);
    }
}
