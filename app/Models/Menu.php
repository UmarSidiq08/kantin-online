<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     public function totalTerjual()
    {
        return $this->hasMany(OrderItem::class)
            ->whereHas('order', function($query) {
                $query->where('status', 'selesai');
            })
            ->sum('quantity') ?? 0;
    }

    public function totalTerjualByDateRange($startDate = null, $endDate = null)
    {
        $query = $this->hasMany(OrderItem::class)
            ->whereHas('order', function($q) {
                $q->where('status', 'selesai');
            });

        if ($startDate && $endDate) {
            $query->whereBetween(DB::raw('DATE(order_items.created_at)'), [$startDate, $endDate]);
        }

        return $query->sum('quantity') ?? 0;
    }
}
