<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $stockService;
    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function getOrders(Request $request)
    {
        $query = Order::query()
            ->select([
                'orders.id',
                'orders.order_no',
                'orders.quantity',
                'orders.status',
                'orders.date',
                'orders.rate',
                'orders.message',
                'customers.name as customer_name',
                'brokers.name as broker_name',
                'transport_companies.name as transport_company',
                'design_nos.name as design_no',
                'items.name as item_name',
            ])
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('brokers', 'orders.broker_id', '=', 'brokers.id')
            ->leftJoin('transport_companies', 'orders.transport_company_id', '=', 'transport_companies.id')
            ->join('design_nos', 'orders.design_no_id', '=', 'design_nos.id')
            ->join('items', 'orders.item_id', '=', 'items.id');


        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customers.name', 'like', "%{$search}%")
                    ->orWhere('orders.order_no', 'like', "%{$search}%")
                    ->orWhere('design_nos.name', 'like', "%{$search}%")
                    ->orWhere('items.name', 'like', "%{$search}%")
                    ->orWhere('orders.quantity', 'like', "%{$search}%")
                    ->orWhere('brokers.name', 'like', "%{$search}%")
                    ->orWhere('transport_companies.name', 'like', "%{$search}%")
                    ->orWhere('orders.rate', 'like', "%{$search}%")
                    ->orWhere('orders.message', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('orders.status', $request->input('status'));
        }

        // Pagination
        $perPage = $request->input('per_page', 10);

        return $query->orderBy('orders.id', 'desc')->paginate($perPage);
    }


    public function updateOrderStatus($id, $status): bool
    {
        $order = Order::find($id);

        if (!$order) {
            return false;
        }

        if ($status == Order::STATUS_CANCELED) {
            $stockData = [
                'item_id'       => $order->item_id,
                'design_no_id'  => $order->design_no_id,
                'mark_no_id'    => $order->mark_no_id,
                'quantity'      => $order->quantity,
                'message'       => 'Order Canceled',
                'stock_manage'  => 1,
            ];

            $this->stockService->create($stockData);
        }

        $order->status = $status;

        return $order->save();
    }


    public function create(array $data): Order
    {
        $order = Order::create(array_merge($data, ['order_no' => 'TEMP']));

        $order->order_no = 'ORD-' . $order->id;
        $order->save();
        return $order;
    }

    public function getLatestOrderNo()
    {
        $latestOrder = Order::orderBy('id', 'desc')->first();

        if ($latestOrder) {
            return 'ORD-' . $latestOrder->id + 1;
        }

        return 'ORD-01';
    }

    public function getOrderById($id)
    {
        return Order::select([
            'orders.id',
            'orders.order_no',
            'orders.quantity',
            'orders.status',
            'orders.date',
            'orders.rate',
            'orders.message',
            'customers.name as customer_name',
            'brokers.name as broker_name',
            'transport_companies.name as transport_company',
            'design_nos.name as design_no',
            'mark_nos.name as mark_name',
            'items.name as item_name',
        ])
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('brokers', 'orders.broker_id', '=', 'brokers.id')
            ->leftJoin('transport_companies', 'orders.transport_company_id', '=', 'transport_companies.id')
            ->join('design_nos', 'orders.design_no_id', '=', 'design_nos.id')
            ->join('mark_nos', 'orders.mark_no_id', '=', 'mark_nos.id')
            ->join('items', 'orders.item_id', '=', 'items.id')
            ->where('orders.id', $id)
            ->first();
    }


    public function updateOrder($id, array $data)
    {
        $order = Order::find($id);

        if (!$order) {
            return false;
        }

        $order->update($data);

        return $order;
    }

    public function deleteOrderById($id): bool
    {
        $order = Order::find($id);

        if (!$order) {
            return false;
        }

        $order = Order::find($id);

        if (!$order) {
            return false;
        }

        if ($order->status != Order::STATUS_CANCELED) {

            $stockData = [
                'item_id'       => $order->item_id,
                'design_no_id'  => $order->design_no_id,
                'mark_no_id'    => $order->mark_no_id,
                'quantity'      => $order->quantity,
                'message'       => 'Order Delete',
                'stock_manage'  => 1,
            ];

            $this->stockService->create($stockData);
        }

        return $order->delete();
    }

    public function getOrderId()
    {

        $order = Order::select('id', 'order_no')->get();

        return $order;
    }

    public function getTopDesigns($limit = 5)
    {
        return DB::table('orders')
            ->join('design_nos', 'orders.design_no_id', '=', 'design_nos.id')
            ->select('design_nos.name as design_no_name', DB::raw('SUM(orders.quantity) as total_quantity'))
            ->groupBy('orders.design_no_id', 'design_nos.name')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();
    }

    public function getSalesTrends($type)
    {

        $data = [];

        switch ($type) {
            case 'day':

                $today = now();
                $startDate = $today->copy()->subDays(6)->startOfDay();
                $endDate = $today->copy()->endOfDay();


                for ($i = 0; $i < 7; $i++) {
                    $date = $startDate->copy()->addDays($i);
                    $label = $date->format('Y-m-d');
                    $data[$label] = ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0];
                }

                $results = DB::table('orders')
                    ->select(
                        DB::raw('DATE(date) as label'),
                        'status',
                        DB::raw('COUNT(*) as total')
                    )
                    ->whereBetween('date', [$startDate, $endDate])
                    ->groupBy('label', 'status')
                    ->get();

                foreach ($results as $row) {
                    $label = $row->label;
                    $status = (string)$row->status;
                    if (isset($data[$label])) {
                        $data[$label][$status] = $row->total;
                    }
                }
                break;

            case 'week':

                $today = now();
                $startDate = $today->copy()->startOfWeek()->subWeeks(3);
                $endDate = $today->copy()->endOfWeek();

                for ($i = 0; $i < 4; $i++) {
                    $weekStart = $startDate->copy()->addWeeks($i);
                    $label = $weekStart->format('o-\WW');
                    $data[$label] = ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0];
                }

                $results = DB::table('orders')
                    ->select(
                        DB::raw("YEARWEEK(date, 1) as yearweek"),
                        'status',
                        DB::raw('COUNT(*) as total')
                    )
                    ->whereBetween('date', [$startDate, $endDate])
                    ->groupBy('yearweek', 'status')
                    ->get();


                foreach ($results as $row) {
                    $yearweek = $row->yearweek;
                    $year = intval(substr($yearweek, 0, 4));
                    $week = intval(substr($yearweek, 4));
                    $label = \Carbon\Carbon::now()->setISODate($year, $week)->startOfWeek()->format('o-\WW');
                    $status = (string)$row->status;
                    if (isset($data[$label])) {
                        $data[$label][$status] = $row->total;
                    }
                }
                break;

            case 'month':

                $today = now();
                $startDate = $today->copy()->subMonths(11)->startOfMonth();
                $endDate = $today->copy()->endOfMonth();

                for ($i = 0; $i < 12; $i++) {
                    $month = $startDate->copy()->addMonths($i);
                    $label = $month->format('Y-m');
                    $data[$label] = ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0];
                }

                $results = DB::table('orders')
                    ->select(
                        DB::raw('DATE_FORMAT(date, "%Y-%m") as label'),
                        'status',
                        DB::raw('COUNT(*) as total')
                    )
                    ->whereBetween('date', [$startDate, $endDate])
                    ->groupBy('label', 'status')
                    ->get();

                foreach ($results as $row) {
                    $label = $row->label;
                    $status = (string)$row->status;
                    if (isset($data[$label])) {
                        $data[$label][$status] = $row->total;
                    }
                }
                break;

            case 'year':

                $today = now();
                $startYear = $today->year - 4;
                $endYear = $today->year;

                for ($year = $startYear; $year <= $endYear; $year++) {
                    $data[(string)$year] = ['0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0];
                }

                $results = DB::table('orders')
                    ->select(
                        DB::raw('YEAR(date) as label'),
                        'status',
                        DB::raw('COUNT(*) as total')
                    )
                    ->whereBetween('date', ["$startYear-01-01", "$endYear-12-31"])
                    ->groupBy('label', 'status')
                    ->get();

                foreach ($results as $row) {
                    $label = (string)$row->label;
                    $status = (string)$row->status;
                    if (isset($data[$label])) {
                        $data[$label][$status] = $row->total;
                    }
                }
                break;

            default:
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid type parameter'
                ], 400);
        }


        return $data;
    }
}
