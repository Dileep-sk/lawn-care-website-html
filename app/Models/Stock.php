<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'mark_no_id',
        'design_no_id',
        'item_id',
        'quantity',
        'message',
        'status',
        'stock_manage'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function designNo()
    {
        return $this->belongsTo(DesignNo::class);
    }
    public function markNo()
    {
        return $this->belongsTo(MarkNo::class, 'mark_no_id', 'id');
    }
}
