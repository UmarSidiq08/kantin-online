<?php

namespace App\Http\Controllers;

use App\Constant;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;




class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $userId = auth()->id();
        $paymentMethod = $request->payment_method;
        $cartItems = Cart::with('menu.canteen')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang kamu kosong.'], 400);
        }

        $canteenId = $cartItems->first()->menu->canteen->id;
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->menu->price * $item->quantity;
        }
        
        $order = Order::create([
            'user_id' => $userId,
            'canteen_id' => $canteenId,
            'total_price' => $total,
            'status' => Constant::ORDER_STATUS['PENDING'],
            'invoice' => 'INV-' . time(),
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentMethod === 'cash' ? 'unpaid' : 'paid',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item->menu_id,
                'quantity' => $item->quantity,
                'price' => $item->menu->price,
            ]);
        }

        // Midtrans Config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitized');
        Config::$is3ds = config('midtrans.3ds');

        // Snap Token
        $user = auth()->user();
        $params = [
            'transaction_details' => [
                'order_id' => $order->invoice,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'callbacks' => [
                'finish' => route('user.payment.success'),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'message' => 'Checkout berhasil',
            'snap_token' => $snapToken,
            'order_id' => $order->id,
        ]);
    }
    public function success(Request $request)
    {
        // Kosongkan keranjang user yang login
        Cart::where('user_id', Auth::id())->delete();

        // Tampilkan halaman sukses
        return view('user.payment.success');
    }

    public function checkoutCash(Request $request)
    {
        try {
            $user = Auth::user();
            $cartItems = Cart::with('menu')->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                Log::warning('Checkout cash gagal: keranjang kosong.');
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
                'canteen_id' => $cartItems->first()->menu->canteen_id, // asumsi semua item dari kantin yg sama
                'payment_method' => 'cash',
                'status' => 'pending',
                'payment_status' => Constant::PAYMENT_STATUS['UNPAID'],
                'total_price' => $total,
                'invoice' => 'INV-' . time(),
            ]);

            Log::info('Order cash berhasil dibuat', ['order_id' => $order->id]);

            // Simpan order item
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item->menu_id,
                    'quantity' => $item->quantity,
                    'price' => $item->menu->price,
                ]);
            }

            // Kosongkan keranjang
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            Log::info('Checkout cash berhasil', ['user_id' => $user->id, 'order_id' => $order->id]);

            return response()->json([
                'success' => true,
                'message' => 'Checkout tunai berhasil.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout cash error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat checkout tunai.'
            ], 500);
        }
    }
}
