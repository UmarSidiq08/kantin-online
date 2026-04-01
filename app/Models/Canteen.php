<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Canteen extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'operating_hours' => 'array',
        'is_open' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
    public function blockedUsers()
    {
        return $this->hasMany(CanteenBlock::class);
    }

    public function isUserBlocked(int $userId): bool
    {
        return $this->blockedUsers()->where('user_id', $userId)->exists();
    }

    public function blockUser(int $userId): void
    {
        $this->blockedUsers()->firstOrCreate(['user_id' => $userId]);
    }

    public function unblockUser(int $userId): void
    {
        $this->blockedUsers()->where('user_id', $userId)->delete();
    }

    /**
     * Cek apakah kantin sedang buka
     */
    public function isOpen()
    {
        // Jika manual di-set tutup
        if (!$this->is_open) {
            return false;
        }

        // Jika belum ada jam operasional di-set, default buka
        if (!$this->operating_hours) {
            return true;
        }

        $now = Carbon::now();
        $currentDay = strtolower($now->format('l')); // monday, tuesday, etc
        $currentTime = $now->format('H:i');

        // Cek jam operasional hari ini
        $todaySchedule = $this->operating_hours[$currentDay] ?? null;

        // Jika tidak ada jadwal untuk hari ini, tapi is_open = true, maka buka
        if (!$todaySchedule) {
            return $this->is_open;
        }

        // Jika jadwal ada tapi di-set tutup untuk hari ini
        if (!$todaySchedule['is_open']) {
            return false;
        }

        $openTime = $todaySchedule['open_time'];
        $closeTime = $todaySchedule['close_time'];

        // Handle case ketika tutup lewat tengah malam (misal: 22:00 - 02:00)
        if ($closeTime < $openTime) {
            return $currentTime >= $openTime || $currentTime <= $closeTime;
        }

        return $currentTime >= $openTime && $currentTime <= $closeTime;
    }

    /**
     * Get jam buka hari ini
     */
    public function getTodaySchedule()
    {
        if (!$this->operating_hours) {
            return null;
        }

        $currentDay = strtolower(Carbon::now()->format('l'));
        return $this->operating_hours[$currentDay] ?? null;
    }

    /**
     * Get waktu buka berikutnya
     */
    public function getNextOpenTime()
    {
        if (!$this->operating_hours) {
            return null;
        }

        $now = Carbon::now();

        // Cek hari ini dulu
        for ($i = 0; $i < 7; $i++) {
            $checkDate = $now->copy()->addDays($i);
            $dayName = strtolower($checkDate->format('l'));
            $schedule = $this->operating_hours[$dayName] ?? null;

            if ($schedule && $schedule['is_open']) {
                $openTime = Carbon::createFromFormat('H:i', $schedule['open_time']);
                $checkDateTime = $checkDate->copy()->setTime($openTime->hour, $openTime->minute);

                // Jika hari ini dan waktu buka masih akan datang
                if ($i == 0 && $checkDateTime > $now) {
                    return $checkDateTime;
                }
                // Jika hari lain
                elseif ($i > 0) {
                    return $checkDateTime;
                }
            }
        }

        return null;
    }
}
