<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserStatusRequest;
use Illuminate\Http\Request;
use App\Services\StockService;
use Exception;
use Illuminate\Http\JsonResponse;


class StockController extends Controller
{
    protected $stockService;


    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
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
}
