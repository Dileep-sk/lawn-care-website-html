<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StockService;
use App\Services\DashboardService;
use Carbon\Carbon;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $stockService;
    protected $dashboardService;
    protected $orderService;
    public function __construct(StockService $stockService, DashboardService $dashboardService, OrderService $orderService)
    {
        $this->stockService = $stockService;
        $this->dashboardService = $dashboardService;
        $this->orderService = $orderService;
    }

    public function outOffStock(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search', null);
            $startDate = $request->input('start_date', Carbon::now()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

            $stocks = $this->stockService->getOutOffStocks($startDate, $endDate, $perPage, $search);

            return response()->json($stocks, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Stock not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    public function getDashboardDetails(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $details = $this->dashboardService->getDashboardDetails($startDate, $endDate);

        return response()->json($details);
    }


    public function topDesigns()
    {
        try {
            $topDesigns = $this->orderService->getTopDesigns(5);

            return response()->json([
                'success' => true,
                'data' => $topDesigns,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch top designs.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function salesTrends(Request $request)
    {
        try {
            $type = $request->get('type', 'day');

            $data = $this->orderService->getSalesTrends($type);

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
