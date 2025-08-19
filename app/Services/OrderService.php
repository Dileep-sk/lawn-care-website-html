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
}
