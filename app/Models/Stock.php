<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'mark_no',
        'design_no',
        'item_name',
        'quantity',
        'message',
        'status',
    ];
}
