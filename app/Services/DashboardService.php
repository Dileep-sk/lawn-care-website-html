<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Stock;
use Carbon\Carbon;

class DashboardService
{
    public function getDashboardDetails($startDate = null, $endDate = null)
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::today()->startOfDay();
        $end   = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::today()->endOfDay();

        $todaysOrders = Order::whereBetween('created_at', ["$startDate 00:00:00", "$endDate 23:59:59"])->count();

        $pendingShipments = Order::where('status', '0')
            ->whereBetween('created_at', ["$startDate 00:00:00", "$endDate 23:59:59"])
            ->count();

        $totalSales = Order::whereBetween('created_at', ["$startDate 00:00:00", "$endDate 23:59:59"])->sum('rate');

        $outOfStock = Stock::query()
            ->selectRaw('
                item_id,
                design_no_id,
                mark_no_id,
                SUM(CASE WHEN stock_manage = 1 THEN quantity ELSE -quantity END) AS total_quantity
            ')
            ->whereBetween('created_at', ["$startDate 00:00:00", "$endDate 23:59:59"])
            ->groupBy('item_id', 'design_no_id', 'mark_no_id')
            ->having('total_quantity', '<=', 0)
            ->get()
            ->count();


        $availableStock = Stock::query()
            ->selectRaw('
                item_id,
                design_no_id,
                mark_no_id,
                SUM(CASE WHEN stock_manage = 1 THEN quantity ELSE -quantity END) AS total_quantity
            ')
            ->whereBetween('created_at', ["$startDate 00:00:00", "$endDate 23:59:59"])
            ->groupBy('item_id', 'design_no_id', 'mark_no_id')
            ->having('total_quantity', '>', 0)
            ->get()
            ->count();

        $leastDesignSold = Order::whereBetween('created_at', ["$startDate 00:00:00", "$endDate 23:59:59"])
            ->select('design_no_id')
            ->selectRaw('SUM(quantity) as total_sold')
            ->groupBy('design_no_id')
            ->orderBy('total_sold', 'asc')
            ->first();

        return [
            'todays_orders'      => $todaysOrders,
            'pending_shipments'  => $pendingShipments,
            'total_sales'        => $totalSales,
            'out_of_stock'       => $outOfStock,
            'available_stock'        => $availableStock,
            'least_design_sold'  => $leastDesignSold?->total_sold ?? 0,
        ];
    }
}
