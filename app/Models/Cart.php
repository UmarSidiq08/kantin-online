<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }

    /**
     * Get the price after applying any active discount
     */
    public function getDiscountedPriceAttribute()
    {
        // Gunakan method yang sudah ada di Menu model
        return $this->menu->getDiscountedPrice();
    }

    /**
     * Get the total price for this cart item (quantity * discounted price)
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->discounted_price;
    }

    /**
     * Get the total savings for this cart item
     */
    public function getTotalSavingsAttribute()
    {
        $originalTotal = $this->quantity * $this->menu->price;
        return $originalTotal - $this->total_price;
    }

    /**
     * Get the active discount for this cart item
     */
    public function getActiveDiscountAttribute()
    {
        return $this->menu->activeDiscount();
    }

    /**
     * Check if this cart item has an active discount
     */
    public function hasActiveDiscount()
    {
        return $this->menu->hasActiveDiscount();
    }
}
