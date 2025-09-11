<?php

namespace App\Http\Controllers;

use App\Constant;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\BalanceTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PaymentController extends Controller
{
    /**
     * Legacy checkout - redirect ke cart dengan info multi-canteen
     */
    public function checkout(Request $request)
    {
        return redirect()->route('user.cart.index')
            ->with('info', 'Silakan checkout per kantin secara terpisah.');
    }

    /**
     * Checkout untuk kantin tertentu
     */
    public function checkoutCanteen($canteenId, Request $request)
    {
        $paymentMethod = $request->input('payment_method', 'cash');

        // Validasi cart items untuk kantin ini
        $cartItems = Cart::with('menu')
            ->where('user_id', Auth::id())
            ->where('canteen_id', $canteenId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Tidak ada item di keranjang untuk kantin ini.');
        }

        // Handle berbagai metode pembayaran
        switch ($paymentMethod) {
            case 'cash':
                return $this->checkoutCashCanteen($canteenId, $cartItems);

            case 'balance':
                return $this->checkoutBalanceCanteen($canteenId, $cartItems);

            case 'digital':
                return $this->checkoutDigitalCanteen($canteenId, $cartItems);

            default:
                return redirect()->route('user.cart.index')
                    ->with('error', 'Metode pembayaran tidak valid.');
        }
    }

    /**
     * Checkout cash untuk kantin tertentu
     */
    private function checkoutCashCanteen($canteenId, $cartItems)
    {
        try {
            $user = Auth::user();
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->menu->price * $item->quantity;
            }

            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id,
                'canteen_id' => $canteenId,
                'payment_method' => 'cash',
                'status' => 'pending',
                'payment_status' => Constant::PAYMENT_STATUS['UNPAID'],
                'total_price' => $total,
                'invoice' => 'CASH-' . time() . '-' . $user->id,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item->menu_id,
                    'quantity' => $item->quantity,
                    'price' => $item->menu->price,
                ]);
            }

            // Hapus hanya cart items untuk kantin ini
            Cart::where('user_id', $user->id)
                ->where('canteen_id', $canteenId)
                ->delete();

            DB::commit();
            session(['current_order_id' => $order->id]);

            return redirect()->route('user.payment.success')
                ->with('success', 'Checkout tunai berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout cash error: ' . $e->getMessage());
            return redirect()->route('user.cart.index')
                ->with('error', 'Terjadi kesalahan saat checkout tunai.');
        }
    }

    /**
     * Checkout balance untuk kantin tertentu
     */
    private function checkoutBalanceCanteen($canteenId, $cartItems)
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->menu->price * $item->quantity;
            }

            if (!$user->hasEnoughBalance($total)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo tidak mencukupi. Saldo Anda: Rp ' . number_format($user->balance, 0, ',', '.') .
                        ', Total belanja: Rp ' . number_format($total, 0, ',', '.')
                ]);
            }

            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id,
                'canteen_id' => $canteenId,
                'payment_method' => 'balance',
                'status' => 'pending',
                'payment_status' => Constant::PAYMENT_STATUS['PAID'],
                'total_price' => $total,
                'invoice' => 'BALANCE-' . time() . '-' . $user->id,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item->menu_id,
                    'quantity' => $item->quantity,
                    'price' => $item->menu->price,
                ]);
            }

            $user->deductBalance(
                $total,
                "Pembayaran order #{$order->invoice}",
                $order->id
            );

            // Hapus hanya cart items untuk kantin ini
            Cart::where('user_id', $user->id)
                ->where('canteen_id', $canteenId)
                ->delete();

            DB::commit();
            session(['current_order_id' => $order->id]);

            return response()->json([
                'success' => true,
                'message' => 'Checkout dengan saldo berhasil. Sisa saldo: Rp ' . number_format($user->balance, 0, ',', '.'),
                'redirect' => route('user.payment.success')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout balance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Checkout digital untuk kantin tertentu
     */
    private function checkoutDigitalCanteen($canteenId, $cartItems)
    {
        try {
            $userId = auth()->id();
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->menu->price * $item->quantity;
            }

            $orderId = 'ORDER-' . time() . '-' . $userId . '-' . $canteenId;

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.sanitized');
            Config::$is3ds = config('midtrans.3ds');

            $user = auth()->user();
            $canteenName = $cartItems->first()->menu->canteen->name;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
                'item_details' => $cartItems->map(function($item) {
                    return [
                        'id' => $item->menu_id,
                        'price' => $item->menu->price,
                        'quantity' => $item->quantity,
                        'name' => $item->menu->name,
                    ];
                })->toArray(),
                'callbacks' => [
                    'finish' => route('user.payment.finish')
                ],
                'custom_field1' => json_encode($cartItems->map(function ($item) {
                    return [
                        'menu_id' => $item->menu_id,
                        'quantity' => $item->quantity,
                        'price' => $item->menu->price
                    ];
                })),
                'custom_field2' => $canteenId,
                'custom_field3' => $userId,
            ];

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'message' => 'Checkout berhasil',
                'snap_token' => $snapToken,
                'order_temp_id' => $orderId,
                'canteen_id' => $canteenId
            ]);

        } catch (\Exception $e) {
            Log::error('Checkout digital error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success(Request $request)
    {
        $orderId = session('current_order_id');

        if ($orderId) {
            $order = Order::with(['items.menu', 'canteen'])->find($orderId);
            session()->forget('current_order_id');
        } else {
            $order = Order::with(['items.menu', 'canteen'])
                ->where('user_id', auth()->id())
                ->where('payment_status', Constant::PAYMENT_STATUS['PAID'])
                ->latest()
                ->first();
        }

        return view('user.payment.success', compact('order'));
    }

    /**
     * Legacy checkout cash - redirect ke cart
     */
    public function checkoutCash(Request $request)
    {
        return redirect()->route('user.cart.index')
            ->with('info', 'Silakan checkout per kantin secara terpisah.');
    }

    /**
     * Legacy checkout balance - redirect ke cart
     */
    public function checkoutBalance(Request $request)
    {
        return redirect()->route('user.cart.index')
            ->with('info', 'Silakan checkout per kantin secara terpisah.');
    }

    /**
     * Top up saldo via Midtrans
     */
    public function topUpBalance(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:1000000',
        ]);

        $userId = auth()->id();
        $amount = $request->amount;
        $orderId = 'TOPUP-' . time() . '-' . $userId;

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitized');
        Config::$is3ds = config('midtrans.3ds');

        $user = auth()->user();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'topup-balance',
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Top Up Saldo Kantin'
                ]
            ],
            'callbacks' => [
                'finish' => route('user.payment.finish')
            ],
            'custom_field1' => 'TOPUP',
            'custom_field2' => $userId,
            'custom_field3' => $amount,
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'message' => 'Top up berhasil diinisialisasi',
            'snap_token' => $snapToken,
            'order_temp_id' => $orderId,
        ]);
    }

    public function handleFinish(Request $request)
    {
        $orderId = $request->get('order_id');
        $statusCode = $request->get('status_code');
        $transactionStatus = $request->get('transaction_status');

        $order = Order::where('invoice', $orderId)->first();

        if ($order && $order->payment_status === Constant::PAYMENT_STATUS['PAID']) {
            return redirect()->route('user.payment.success');
        } else {
            return redirect()->route('user.cart.index')
                ->with('info', 'Pembayaran belum selesai. Silakan lanjutkan pembayaran atau coba lagi.');
        }
    }
}
