<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StockService;
use App\Services\DashboardService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    protected $stockService;
    protected $dashboardService;

    public function __construct(StockService $stockService, DashboardService $dashboardService)
    {
        $this->stockService = $stockService;
        $this->dashboardService = $dashboardService;
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
}
