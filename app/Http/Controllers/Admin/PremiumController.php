<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Canteen;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Constant;

class PremiumController extends Controller
{
    public function show()
    {
        $features = [
            ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Laporan Penjualan', 'description' => 'Lihat laporan penjualan dengan grafik dan filter tanggal'],
            ['icon' => 'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Export Data', 'description' => 'Export laporan ke Excel dan PDF untuk arsip'],
            ['icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'title' => 'Grafik Analytics', 'description' => 'Visualisasi data penjualan dengan grafik interaktif'],
            ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'Filter Periode', 'description' => 'Filter laporan berdasarkan tanggal dan periode'],
            ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Data Real-time', 'description' => 'Akses data penjualan secara real-time dan up-to-date'],
            ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Analisis Mendalam', 'description' => 'Insight bisnis dengan analisis data yang komprehensif'],
        ];
        return view('admin.premium.upgrade', compact('features'));
    }

    public function pay(Request $request)
    {
        $canteen = Auth::user()->canteen;
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        $orderId = 'PREMIUM-' . time();
        $canteen->midtrans_order_id = $orderId;
        $canteen->save();
        $params = ['transaction_details' => ['order_id' => $orderId, 'gross_amount' => 49900], 'customer_details' => ['first_name' => $canteen->name]];
        $snapToken = Snap::getSnapToken($params);
        return response()->json(['token' => $snapToken]);
    }

public function callback(Request $request)
{
    try {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $notif = new Notification();

        $transactionStatus = $notif->transaction_status;
        $fraudStatus = $notif->fraud_status;
        $orderId = $notif->order_id;

        // Log untuk debugging
        Log::info('Midtrans Callback Received', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $notif->payment_type ?? 'unknown'
        ]);

        // PREMIUM SUBSCRIPTION
        if (str_starts_with($orderId, 'PREMIUM-')) {
            $canteen = Canteen::where('midtrans_order_id', $orderId)->first();

            if (!$canteen) {
                Log::error('Canteen not found for premium callback', ['order_id' => $orderId]);
                return response()->json(['message' => 'Canteen not found'], 200); // Tetap return 200
            }

            if ($transactionStatus == 'settlement' || ($transactionStatus == 'capture' && $fraudStatus == 'accept')) {
                $canteen->is_premium = true;
                $canteen->save();
                Log::info('Premium subscription activated', ['canteen_id' => $canteen->id]);
            }
        }
        // TOP UP BALANCE
        elseif (str_starts_with($orderId, 'TOPUP-')) {
            if ($transactionStatus == 'settlement' || ($transactionStatus == 'capture' && $fraudStatus == 'accept')) {
                try {
                    $userId = $notif->custom_field2;
                    $amount = $notif->custom_field3;

                    if (!$userId || !$amount) {
                        Log::error('Missing top-up data in callback', [
                            'order_id' => $orderId,
                            'custom_field2' => $notif->custom_field2,
                            'custom_field3' => $notif->custom_field3
                        ]);
                        return response()->json(['message' => 'Missing top-up data'], 200); // Tetap return 200
                    }

                    $user = User::find($userId);
                    if (!$user) {
                        Log::error('User not found for top-up callback', [
                            'user_id' => $userId,
                            'order_id' => $orderId
                        ]);
                        return response()->json(['message' => 'User not found'], 200); // Tetap return 200
                    }

                    // Cek duplikasi - apakah sudah pernah diproses
                    $existingTransaction = \App\Models\BalanceTransaction::where('description', 'like', "%{$orderId}%")
                        ->where('user_id', $userId)
                        ->first();

                    if ($existingTransaction) {
                        Log::info('Top-up already processed', ['order_id' => $orderId]);
                        return response()->json(['message' => 'Already processed'], 200);
                    }

                    DB::beginTransaction();

                    $user->addBalance(
                        $amount,
                        "Top up saldo via Midtrans - #{$orderId}",
                        $orderId
                    );

                    DB::commit();

                    Log::info('Top-up successful', [
                        'user_id' => $userId,
                        'amount' => $amount,
                        'order_id' => $orderId,
                        'new_balance' => $user->balance
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error processing top-up after payment: ' . $e->getMessage(), [
                        'order_id' => $orderId,
                        'user_id' => $userId ?? 'unknown',
                        'amount' => $amount ?? 'unknown',
                        'trace' => $e->getTraceAsString()
                    ]);
                    return response()->json(['message' => 'Error processing top-up'], 200); // Tetap return 200
                }
            } elseif ($transactionStatus == 'pending') {
                Log::info('Top-up payment pending', ['order_id' => $orderId]);
            } else {
                Log::info('Top-up payment failed/cancelled', [
                    'order_id' => $orderId,
                    'status' => $transactionStatus
                ]);
            }
        }
        // ORDER PAYMENT
        elseif (str_starts_with($orderId, 'ORDER-')) {
            if ($transactionStatus == 'settlement' || ($transactionStatus == 'capture' && $fraudStatus == 'accept')) {
                try {
                    // Cek apakah order sudah ada
                    $existingOrder = Order::where('invoice', $orderId)->first();

                    if ($existingOrder) {
                        // Update status jika sudah ada
                        $existingOrder->payment_status = Constant::PAYMENT_STATUS['PAID'];
                        $existingOrder->save();

                        Log::info('Order payment status updated', ['order_id' => $existingOrder->id]);
                        return response()->json(['message' => 'Order already exists, status updated'], 200);
                    }

                    $cartData = json_decode($notif->custom_field1, true);
                    $canteenId = $notif->custom_field2;
                    $userId = $notif->custom_field3;

                    if (!$cartData || !$canteenId || !$userId) {
                        Log::error('Missing cart data in callback', [
                            'order_id' => $orderId,
                            'custom_field1' => $notif->custom_field1,
                            'custom_field2' => $notif->custom_field2,
                            'custom_field3' => $notif->custom_field3
                        ]);
                        return response()->json(['message' => 'Missing cart data'], 200); // Tetap return 200
                    }

                    DB::beginTransaction();

                    $order = Order::create([
                        'user_id' => $userId,
                        'canteen_id' => $canteenId,
                        'payment_method' => 'digital',
                        'status' => Constant::ORDER_STATUS['PENDING'],
                        'payment_status' => Constant::PAYMENT_STATUS['PAID'],
                        'total_price' => $notif->gross_amount,
                        'invoice' => $orderId
                    ]);

                    foreach ($cartData as $item) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'menu_id' => $item['menu_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price']
                        ]);
                    }

                    // Hapus cart items
                    Cart::where('user_id', $userId)
                        ->where('canteen_id', $canteenId)
                        ->delete();

                    DB::commit();

                    Log::info('Order created successfully', [
                        'order_id' => $order->id,
                        'invoice' => $orderId
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error creating order after payment: ' . $e->getMessage(), [
                        'order_id' => $orderId,
                        'trace' => $e->getTraceAsString()
                    ]);
                    return response()->json(['message' => 'Error creating order'], 200); // Tetap return 200
                }
            } elseif ($transactionStatus == 'pending') {
                Log::info('Order payment pending', ['order_id' => $orderId]);
            } else {
                Log::info('Order payment failed/cancelled', [
                    'order_id' => $orderId,
                    'status' => $transactionStatus
                ]);
            }
        } else {
            Log::warning('Unknown order type', ['order_id' => $orderId]);
        }

        // PENTING: Selalu return 200 agar Midtrans tidak kirim ulang
        return response()->json(['message' => 'Callback handled successfully'], 200);

    } catch (\Exception $e) {
        Log::error('Midtrans callback error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        // Tetap return 200 untuk menghentikan retry dari Midtrans
        return response()->json(['message' => 'Error processed'], 200);
    }
}
}
