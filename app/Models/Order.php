<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     protected $fillable = [
        'customer_name',
        'order_no',
        'date',
        'broker_name',
        'transport_company',
        'design_no',
        'item_name',
        'quantity',
        'rate',
        'status',
        'message',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
    ];
}
