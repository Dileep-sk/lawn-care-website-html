<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\CustomerService;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Get active customers (status = 1)
     */
    public function index(): JsonResponse
    {
        $customers = $this->customerService->getActiveCustomers();

        return response()->json([
            'success' => true,
            'data' => $customers
        ], 200);
    }
}
