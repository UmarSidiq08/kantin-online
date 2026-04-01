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
    protected $table = 'produk';
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
            $query->whereBetween(DB::raw('DATE(detailpenjualan.created_at)'), [$startDate, $endDate]);
        }

        return $query->sum('quantity') ?? 0;
    }


    public function isStokTersedia(): bool
    {
        return $this->stok > 0;
    }


    public function isStokCukup(int $jumlah): bool
    {
        return $this->stok >= $jumlah;
    }

    public function kurangiStok(int $jumlah): void
    {
        if (!$this->isStokCukup($jumlah)) {
            throw new \Exception("Stok {$this->name} tidak mencukupi. Stok tersisa: {$this->stok}");
        }
        $this->decrement('stok', $jumlah);
    }

    public function tambahStok(int $jumlah): void
    {
        $this->increment('stok', $jumlah);
    }


    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function activeDiscount()
    {
        return $this->discounts()
            ->active()
            ->where(function ($query) {
                $now = now();
                $today = $now->toDateString();
                $currentTime = $now->format('H:i:s');

                $query->where(function ($q) {
                    $q->whereNull('start_date')
                        ->whereNull('end_date')
                        ->whereNull('start_time')
                        ->whereNull('end_time');
                })
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
            ->orderBy('value', 'desc')
            ->first();
    }

    public function hasActiveDiscount()
    {
        return $this->activeDiscount() !== null;
    }

    public function getDiscountedPrice()
    {
        $activeDiscount = $this->activeDiscount();
        if (!$activeDiscount) {
            return $this->price;
        }
        return $activeDiscount->getPriceAfterDiscount($this->price);
    }

    public function getDiscountAmount()
    {
        $activeDiscount = $this->activeDiscount();
        if (!$activeDiscount) {
            return 0;
        }
        return $activeDiscount->getDiscountAmount($this->price);
    }

    public function getDiscountPercentage()
    {
        $activeDiscount = $this->activeDiscount();
        if (!$activeDiscount) {
            return 0;
        }
        if ($activeDiscount->type === 'percentage') {
            return $activeDiscount->value;
        }
        return ($activeDiscount->value / $this->price) * 100;
    }

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
