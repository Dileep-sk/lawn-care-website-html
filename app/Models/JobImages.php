<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobImages extends Model
{
    protected $table = 'order_jobs_images';

    protected $fillable = [
        'job_id',
        'image'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }
}
