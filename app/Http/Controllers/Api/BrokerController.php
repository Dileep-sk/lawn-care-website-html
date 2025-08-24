<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BrokerService;
use Illuminate\Http\JsonResponse;

class BrokerController extends Controller
{
    protected $brokerService;

    public function __construct(BrokerService $brokerService)
    {
        $this->brokerService = $brokerService;
    }

    /**
     * Return active brokers.
     */
    public function index(): JsonResponse
    {
        $brokers = $this->brokerService->getActiveBrokers();

        return response()->json([
            'success' => true,
            'data' => $brokers,
        ]);
    }
}
