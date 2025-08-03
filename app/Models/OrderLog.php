<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    protected $fillable = [
        'order_id',
        'canteen_id',
        'user_id',
        'status',
        'total_price',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'orderlog_id');
    }
}
