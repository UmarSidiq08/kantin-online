<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    public function getCanteenNameAttribute()
    {
        return $this->canteen ? $this->canteen->name : 'Unknown Canteen';
    }
}
