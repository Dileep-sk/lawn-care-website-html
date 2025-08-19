<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Get all orders with optional search and pagination.
     */


    public function index(Request $request)
    {
        $orders = $this->orderService->getOrders($request);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
}
