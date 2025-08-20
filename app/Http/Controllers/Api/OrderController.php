<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
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
                return response()->json(['message' => 'Order status updated successfully.']);
            }

            return response()->json(['message' => 'Order not found or status not updated.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update Order status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->create($request->validated());

            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update Order status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getLatestOrderId(): JsonResponse
    {
        try {

            $latestOrderNo = $this->orderService->getLatestOrderNo();

            return response()->json([
                'latest_order_no' => $latestOrderNo
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to get latest Order No.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($id);

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(StoreOrderRequest $request, $id): JsonResponse
    {
        try {
            $order = $this->orderService->updateOrder($id, $request->all());

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            return response()->json([
                'message' => 'Order updated successfully',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id): JsonResponse
    {
        try {
            $result = $this->orderService->deleteOrderById($id);

            if ($result) {
                return response()->json(['message' => 'Order deleted successfully.']);
            }

            return response()->json(['message' => 'Order not found.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
