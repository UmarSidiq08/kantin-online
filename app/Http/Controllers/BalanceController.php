<?php

namespace App\Http\Controllers;

use App\Models\BalanceTransaction;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function history(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $transactions = $user
            ->balanceTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.balance.history', compact('transactions'));
    }
    public function getBalance()
    {
        /** @var User $user */
        $user = Auth::user();

        return response()->json([
            'balance' => $user->balance,
            'formatted_balance' => 'Rp ' . number_format($user->balance, 0, ',', '.')
        ]);
    }
}
