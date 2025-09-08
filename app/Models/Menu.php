<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    // Tambah ke Model Menu yang udah ada
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?: 0;
    }

    public function totalRatings()
    {
        return $this->ratings()->count();
    }
}
