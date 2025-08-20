<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderService
{

    public function getOrders(Request $request)
    {
        $query = Order::select([
            'id',
            'order_no',
            'design_no',
            'item_name',
            'quantity',
            'status',
            // 'customer_name',
            // 'date',
            // 'broker_name',
            // 'transport_company',
            // 'rate',
        ]);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('order_no', 'like', "%{$search}%")
                    ->orWhere('design_no', 'like', "%{$search}%")
                    ->orWhere('item_name', 'like', "%{$search}%")
                    ->orWhere('quantity', 'like', "%{$search}%");
                // ->orWhere('broker_name', 'like', "%{$search}%")
                // ->orWhere('transport_company', 'like', "%{$search}%")
                // ->orWhere('rate', 'like', "%{$search}%")
                // ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $perPage = $request->input('per_page', 10);
        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function updateOrderStatus($id, $status): bool
    {
        $order = Order::find($id);

        if (!$order) {
            return false;
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
            return 'ORD' . $latestOrder->id + 1;
        }

        return 'ORD-00001';
    }

    public function getOrderById($id)
    {
        return Order::find($id);
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

        return $order->delete();
    }
}
