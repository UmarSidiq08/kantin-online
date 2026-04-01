<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\BalanceTransaction;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'balance', 'alamat',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'balance' => 'decimal:2',
    ];

    public function canteen()
    {
        return $this->hasOne(Canteen::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function balanceTransactions()
    {
        return $this->hasMany(BalanceTransaction::class);
    }

    // Tambahan relasi orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Tambahan relasi blokir
    public function canteenBlocks()
    {
        return $this->hasMany(CanteenBlock::class);
    }

    /**
     * Cek apakah user diblokir oleh kantin tertentu
     */
    public function isBlockedBy(int $canteenId): bool
    {
        return $this->canteenBlocks()->where('canteen_id', $canteenId)->exists();
    }

    public function hasEnoughBalance($amount)
    {
        return $this->balance >= $amount;
    }

    public function addBalance($amount, $description = null, $referenceId = null)
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->save();

        BalanceTransaction::create([
            'user_id' => $this->id,
            'type' => 'top_up',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'reference_id' => $referenceId,
            'description' => $description ?? "Top up saldo sebesar Rp " . number_format($amount, 0, ',', '.'),
        ]);

        return $this;
    }

    public function deductBalance($amount, $description = null, $referenceId = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Saldo tidak mencukupi');
        }

        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->save();

        BalanceTransaction::create([
            'user_id' => $this->id,
            'type' => 'payment',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'reference_id' => $referenceId,
            'description' => $description ?? "Pembayaran sebesar Rp " . number_format($amount, 0, ',', '.'),
        ]);

        return $this;
    }
}
