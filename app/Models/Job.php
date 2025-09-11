<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public const MOVE_JOB_IN_STOCK = 4;

    protected $table = 'order_jobs';

    protected $fillable = [
        'id',
        'customer_id',
        'mark_no_id',
        'design_no_id',
        'item_id',
        'quantity',
        'order_no_id',
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

    public function images()
    {
        return $this->hasMany(JobImages::class, 'job_id', 'id');
    }
}
