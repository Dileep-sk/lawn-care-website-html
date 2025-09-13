<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'banner_image',
        'banner_title',
        'banner_short_description',
        'service_title',
        'service_description',
        'status',
    ];
}
