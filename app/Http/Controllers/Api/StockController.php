<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateUserStatusRequest;
use Illuminate\Http\Request;
use App\Services\StockService;
use App\Services\ItemService;
use App\Services\DesignNoService;
use App\Services\MarkNoService;
use Exception;
use Illuminate\Http\JsonResponse;


class StockController extends Controller
{
    protected $stockService;
    protected $itemService;
    protected $designNoService;
    protected $markNoService;

    public function __construct(StockService $stockService, ItemService $itemService, DesignNoService $designNoService, MarkNoService $markNoService)
    {
        $this->stockService = $stockService;
        $this->itemService = $itemService;
        $this->designNoService = $designNoService;
        $this->markNoService = $markNoService;
    }


    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', null);

        $stocks = $this->stockService->getStocksPaginated($perPage, $search);

        return response()->json($stocks, 200);
    }


    public function updateStatus(UpdateUserStatusRequest $request, $id): JsonResponse
    {
        try {
            $updated = $this->stockService->updateStockStatus($id, $request->status);

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

    public function destroy($id): JsonResponse
    {
        try {
            $result = $this->stockService->deleteStockById($id);

            if ($result) {
                return response()->json(['message' => 'Stock deleted successfully.']);
            }

            return response()->json(['message' => 'Stock not found.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Stock.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreStockRequest $request): JsonResponse
    {
        try {

            $item = $this->itemService->createAndFind($request->item_name);
            $designNo = $this->designNoService->createAndFind($request->design_no);
            $markNo = $this->markNoService->createAndFind($request->mark_no);

            $stockData = array_merge($request->validated(), ['item_name' => $item]);
            $stockData['design_no'] = $designNo;
            $stockData['mark_no'] = $markNo;

            $stock = $this->stockService->create($stockData);
            return response()->json([
                'message' => 'Stock created successfully',
                'data' => $stock
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function view($id)
    {
        try {
            $stock = $this->stockService->getStcokById($id);

            if (!$stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $stock
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching the stock.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function update(StoreStockRequest $request, int $id): JsonResponse
    {
        try {
            $user = $this->stockService->updateStcok($id, $request->validated());

            return response()->json([
                'message' => 'User updated successfully.',
                'data' => $user,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }
    }
}
