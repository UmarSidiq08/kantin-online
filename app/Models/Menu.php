<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'end_date' => 'date',
        'end_time' => 'datetime',
  
    ];

    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

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
            ->whereHas('order', function ($query) {
                $query->where('status', 'selesai');
            })
            ->sum('quantity') ?? 0;
    }

    public function totalTerjualByDateRange($startDate = null, $endDate = null)
    {
        $query = $this->hasMany(OrderItem::class)
            ->whereHas('order', function ($q) {
                $q->where('status', 'selesai');
            });

        if ($startDate && $endDate) {
            $query->whereBetween(DB::raw('DATE(order_items.created_at)'), [$startDate, $endDate]);
        }

        return $query->sum('quantity') ?? 0;
    }

    // ========== DISCOUNT RELATIONS & METHODS ==========

    // Relasi ke discount
    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    // Dapatkan diskon yang sedang aktif dan berlaku sekarang
    public function activeDiscount()
    {
        return $this->discounts()
            ->active()
            ->where(function ($query) {
                $now = now();
                $today = $now->toDateString();
                $currentTime = $now->format('H:i:s');

                // Diskon tanpa batasan tanggal dan jam
                $query->where(function ($q) {
                    $q->whereNull('start_date')
                        ->whereNull('end_date')
                        ->whereNull('start_time')
                        ->whereNull('end_time');
                })
                    // Atau diskon dengan batasan tanggal yang masih berlaku
                    ->orWhere(function ($q) use ($today, $currentTime) {
                        $q->where(function ($dateQuery) use ($today) {
                            $dateQuery->where('start_date', '<=', $today)
                                ->where('end_date', '>=', $today);
                        })
                            ->where(function ($timeQuery) use ($currentTime) {
                                $timeQuery->whereNull('start_time')
                                    ->whereNull('end_time')
                                    ->orWhere(function ($tq) use ($currentTime) {
                                        $tq->where('start_time', '<=', $currentTime)
                                            ->where('end_time', '>=', $currentTime);
                                    });
                            });
                    });
            })
            ->orderBy('value', 'desc') // prioritas diskon terbesar
            ->first();
    }

    // Cek apakah menu sedang ada diskon
    public function hasActiveDiscount()
    {
        return $this->activeDiscount() !== null;
    }

    // Dapatkan harga setelah diskon
    public function getDiscountedPrice()
    {
        $activeDiscount = $this->activeDiscount();

        if (!$activeDiscount) {
            return $this->price;
        }

        return $activeDiscount->getPriceAfterDiscount($this->price);
    }

    // Dapatkan jumlah diskon dalam rupiah
    public function getDiscountAmount()
    {
        $activeDiscount = $this->activeDiscount();

        if (!$activeDiscount) {
            return 0;
        }

        return $activeDiscount->getDiscountAmount($this->price);
    }

    // Dapatkan persentase diskon (untuk display)
    public function getDiscountPercentage()
    {
        $activeDiscount = $this->activeDiscount();

        if (!$activeDiscount) {
            return 0;
        }

        if ($activeDiscount->type === 'percentage') {
            return $activeDiscount->value;
        }

        // Konversi fixed amount ke persentase
        return ($activeDiscount->value / $this->price) * 100;
    }

    // Accessor untuk formatted price with discount
    public function getFormattedPriceAttribute()
    {
        if ($this->hasActiveDiscount()) {
            $originalPrice = number_format($this->price, 0, ',', '.');
            $discountedPrice = number_format($this->getDiscountedPrice(), 0, ',', '.');

            return [
                'original' => 'Rp ' . $originalPrice,
                'discounted' => 'Rp ' . $discountedPrice,
                'has_discount' => true
            ];
        }

        return [
            'original' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'discounted' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'has_discount' => false
        ];
    }

    // Accessor untuk discount info
    public function getDiscountInfoAttribute()
    {
        $activeDiscount = $this->activeDiscount();

        if (!$activeDiscount) {
            return null;
        }

        return [
            'type' => $activeDiscount->type,
            'value' => $activeDiscount->value,
            'formatted_value' => $activeDiscount->formatted_value,
            'amount' => $this->getDiscountAmount(),
            'percentage' => round($this->getDiscountPercentage()),
            'description' => $activeDiscount->description,
            'end_date' => $activeDiscount->end_date?->format('d/m/Y'),
            'end_time' => $activeDiscount->end_time?->format('H:i')
        ];
    }
}
