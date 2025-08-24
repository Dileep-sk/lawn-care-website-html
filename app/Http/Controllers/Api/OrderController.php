<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\DesignNo;
use App\Models\Item;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Services\TransportCompanyService;
use App\Services\BrokerService;
use App\Services\CustomerService;
use App\Services\StockService;

class OrderController extends Controller
{
    protected $orderService;
    protected $transportCompanyService;
    protected $brokerService;
    protected $customerService;
    protected $stockService;

    public function __construct(OrderService $orderService, TransportCompanyService $transportCompanyService, BrokerService $brokerService, CustomerService $customerService, StockService $stockService)
    {
        $this->orderService = $orderService;
        $this->transportCompanyService = $transportCompanyService;
        $this->brokerService = $brokerService;
        $this->customerService = $customerService;
        $this->stockService = $stockService;
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
            $isDesignNameExit = DesignNo::find($request->design_no);
            if (empty($isDesignNameExit)) {

                return response()->json([
                    'message' => 'Design No does not Please select correct Design No.',
                    'error' => ""
                ], 500);
            }

            $isitemExit = Item::find($request->item_name);
            if (empty($isitemExit)) {

                return response()->json([
                    'message' => 'Item Name No does not Please select correct Item Name.',
                    'error' => ""
                ], 500);
            }

            $transport_company = $this->transportCompanyService->createAndFind($request->transport_company);
            $broker_name = $this->brokerService->createAndFind($request->broker_name);
            $customer_name = $this->customerService->createAndFind($request->customer_name);
            $orderData = array_merge($request->validated(), [
                'transport_company_id' => $transport_company,
                'broker_id' => $broker_name,
                'customer_id' => $customer_name,
                'design_no_id' => $request->design_no,
                'item_id' => $request->item_name,
            ]);

            $stockData = [
                'item_id' => $request->item_name,
                'design_no_id' => $request->design_no,
                'mark_no_id' => 1,
                'quantity' => $request->quantity,
                'message ' => 'Order Created',
                'stock_manage' => 0,
            ];

            $stock = $this->stockService->create($stockData);

            if (isset($stock)) {
                $order = $this->orderService->create($orderData);
            }

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
