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
    public function checkout(Request $request)
    {
        $userId = auth()->id();
        $cartItems = Cart::with('menu.canteen')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang kamu kosong.'], 400);
        }

        $canteenId = $cartItems->first()->menu->canteen->id;
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item->menu->price * $item->quantity;
        }

        $orderId = 'ORDER-' . time() . '-' . $userId;

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitized');
        Config::$is3ds = config('midtrans.3ds');

        $user = auth()->user();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'callbacks' => [
                'finish' => route('user.payment.finish') // Ganti ke finish handler
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
        ]);
    }

   public function success(Request $request)
{
    $orderId = session('current_order_id');

    if ($orderId) {
        $order = Order::with(['items.menu'])->find($orderId);
        session()->forget('current_order_id'); // Clear session setelah dipakai
    } else {
        // Fallback untuk Midtrans atau akses langsung
        $order = Order::with(['items.menu'])
            ->where('user_id', auth()->id())
            ->where('payment_status', Constant::PAYMENT_STATUS['PAID'])
            ->latest()
            ->first();
    }

    return view('user.payment.success', compact('order'));
}

    public function checkoutCash(Request $request)
    {
        try {
            $user = Auth::user();
            $cartItems = Cart::with('menu')->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kamu kosong.'
                ]);
            }

            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->menu->price * $item->quantity;
            }

            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id,
                'canteen_id' => $cartItems->first()->menu->canteen_id,
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

            Cart::where('user_id', $user->id)->delete();
            DB::commit();
            session(['current_order_id' => $order->id]);

            return response()->json([
                'success' => true,
                'message' => 'Checkout tunai berhasil.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout cash error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat checkout tunai.'
            ], 500);
        }
    }

    /**
     * Checkout dengan saldo kantin
     */
 public function checkoutBalance(Request $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $cartItems = Cart::with('menu')->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kamu kosong.'
                ]);
            }

            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->menu->price * $item->quantity;
            }

            // Check apakah saldo mencukupi menggunakan method dari model
            if (!$user->hasEnoughBalance($total)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo tidak mencukupi. Saldo Anda: Rp ' . number_format($user->balance, 0, ',', '.') .
                        ', Total belanja: Rp ' . number_format($total, 0, ',', '.')
                ]);
            }

            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'canteen_id' => $cartItems->first()->menu->canteen_id,
                'payment_method' => 'balance',
                'status' => 'pending',
                'payment_status' => Constant::PAYMENT_STATUS['PAID'],
                'total_price' => $total,
                'invoice' => 'BALANCE-' . time() . '-' . $user->id,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item->menu_id,
                    'quantity' => $item->quantity,
                    'price' => $item->menu->price,
                ]);
            }

            // Deduct balance menggunakan method dari model
            $user->deductBalance(
                $total,
                "Pembayaran order #{$order->invoice}",
                $order->id
            );

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();
            session(['current_order_id' => $order->id]);

            return response()->json([
                'success' => true,
                'message' => 'Checkout dengan saldo berhasil. Sisa saldo: Rp ' . number_format($user->balance, 0, ',', '.')
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
     * Top up saldo via Midtrans
     */
    public function topUpBalance(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:1000000', // Min 10rb, Max 1jt
        ]);

        $userId = auth()->id();
        $amount = $request->amount;

        // Create unique order ID untuk top up
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
                'finish' => route('user.payment.finish') // Ganti ke finish handler
            ],

            // Mark this as topup transaction
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

        // Cek apakah order sudah dibuat (berarti payment sukses)
        $order = Order::where('invoice', $orderId)->first();

        if ($order && $order->payment_status === Constant::PAYMENT_STATUS['PAID']) {
            // Order ada dan sudah dibayar -> redirect ke success
            return redirect()->route('user.payment.success');
        } else {
            // Order belum dibuat atau belum dibayar -> kembali ke cart
            return redirect()->route('user.cart.index')
                ->with('info', 'Pembayaran belum selesai. Silakan lanjutkan pembayaran atau coba lagi.');
        }
    }
}
