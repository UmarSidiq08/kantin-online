<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\BalanceTransaction;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance', // Tambahkan balance ke fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'balance' => 'decimal:2', // Cast balance sebagai decimal
    ];

    public function canteen()
    {
        return $this->hasOne(Canteen::class);
    }

    public function balanceTransactions()
    {
        return $this->hasMany(BalanceTransaction::class);
    }

    /**
     * Check apakah saldo mencukupi
     */
    public function hasEnoughBalance($amount)
    {
        return $this->balance >= $amount;
    }

    /**
     * Method untuk menambah saldo
     */
    public function addBalance($amount, $description = null, $referenceId = null)
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->save();

        // Record transaksi
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

    /**
     * Method untuk mengurangi saldo (pembayaran)
     */
    public function deductBalance($amount, $description = null, $referenceId = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Saldo tidak mencukupi');
        }

        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->save();

        // Record transaksi
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
