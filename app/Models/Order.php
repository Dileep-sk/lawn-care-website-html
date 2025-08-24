<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public const STATUS_CANCELED = 4;

     protected $fillable = [
        'customer_id',
        'order_no',
        'date',
        'broker_id',
        'transport_company_id',
        'design_no_id',
        'item_id',
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
