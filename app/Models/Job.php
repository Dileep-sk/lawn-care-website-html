<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'order_jobs';

    protected $fillable = [
        'customer_name',
        'design_no',
        'image',
        'quantity',
        'order_no',
        'status',
        'matching_1',
        'matching_2',
        'matching_3',
        'matching_4',
        'matching_5',
        'matching_6',
        'matching_7',
        'matching_8',
        'message',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'status' => 'integer',
    ];
}
