<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderStatusRequest;
use Exception;
use Illuminate\Http\JsonResponse;

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

    public function updateStatus(UpdateOrderStatusRequest $request, $id): JsonResponse
    {
        try {
            $updated = $this->orderService->updateOrderStatus($id, $request->status);

            if ($updated) {
                return response()->json(['message' => 'Stock status updated successfully.']);
            }

            return response()->json(['message' => 'Stock not found or status not updated.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update Stock status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
