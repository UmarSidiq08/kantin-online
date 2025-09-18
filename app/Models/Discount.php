<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    protected $guarded = [];

    protected $dates = ['start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'end_time' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Scope untuk discount yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Cek apakah diskon berlaku sekarang
     */
    public function isValidNow()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        // Cek tanggal
        $dateValid = true;
        if ($this->start_date && $this->end_date) {
            $dateValid = $today >= $this->start_date->format('Y-m-d') &&
                        $today <= $this->end_date->format('Y-m-d');
        } elseif ($this->start_date) {
            $dateValid = $today >= $this->start_date->format('Y-m-d');
        } elseif ($this->end_date) {
            $dateValid = $today <= $this->end_date->format('Y-m-d');
        }

        if (!$dateValid) {
            return false;
        }

        // Cek waktu jika ada
        if ($this->start_time && $this->end_time) {
            return $currentTime >= $this->start_time && $currentTime <= $this->end_time;
        }

        return true;
    }

    /**
     * Hitung harga setelah diskon
     */
    public function getPriceAfterDiscount($originalPrice)
    {
        if ($this->type === 'percentage') {
            $discountAmount = ($this->value / 100) * $originalPrice;
            return $originalPrice - $discountAmount;
        }

        return $originalPrice - $this->value;
    }

    /**
     * Hitung jumlah diskon dalam rupiah
     */
    public function getDiscountAmount($originalPrice)
    {
        if ($this->type === 'percentage') {
            return ($this->value / 100) * $originalPrice;
        }

        return $this->value;
    }

    /**
     * Accessor untuk formatted value
     */
    public function getFormattedValueAttribute()
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }

        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }

    /**
     * Accessor untuk status
     */
    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'Tidak Aktif';
        }

        if (!$this->isValidNow()) {
            $now = now();
            $today = $now->toDateString();

            if ($this->start_date && $today < $this->start_date->format('Y-m-d')) {
                return 'Akan Berlaku';
            }

            if ($this->end_date && $today > $this->end_date->format('Y-m-d')) {
                return 'Sudah Berakhir';
            }

            return 'Tidak Berlaku';
        }

        return 'Berlaku Sekarang';
    }
}
