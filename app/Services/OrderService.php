<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\StockService;

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
                    ->orWhere('design_nos.name  ', 'like', "%{$search}%")
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

        $order->order_no = 'ORD' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
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

    public function getOrderId(){

          $order = Order::select('id','order_no')->get();

          return $order;

    }
}
