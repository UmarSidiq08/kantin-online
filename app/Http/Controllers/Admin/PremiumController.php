<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Midtrans\Notification;
use App\Models\Canteen;
class PremiumController extends Controller
{
    public function show()
    {
        return view('admin.premium.upgrade');
    }

    public function pay(Request $request)
    {
        $canteen = Auth::user()->canteen;

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Buat order_id unik
        $orderId = 'PREMIUM-' . time();

        // Simpan order_id di database
        $canteen->midtrans_order_id = $orderId;
        $canteen->save();

        // Data transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => 49900,
            ],
            'customer_details' => [
                'first_name' => $canteen->name,
                
            ],
        ];

        // Dapatkan Snap Token
        $snapToken = Snap::getSnapToken($params);

        return response()->json(['token' => $snapToken]);
    }

    public function callback(Request $request)
    {
        try {
            // Konfigurasi Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');

            // Ambil notifikasi
            $notif = new Notification();
            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id;

            Log::info("Callback diterima: order_id={$orderId}, status={$transactionStatus}");

            // Cari user berdasarkan order_id
            $canteen = Canteen::where('midtrans_order_id', $orderId)->first();

            if (!$canteen) {
                Log::warning("User tidak ditemukan untuk order ID: {$orderId}");
                return response()->json(['message' => 'User not found'], 404);
            }

            // Proses status pembayaran
            switch ($transactionStatus) {
                case 'capture':
                case 'settlement':
                    $canteen->is_premium = true;
                    $canteen->save();
                    Log::info("User {$canteen->id} berhasil menjadi premium");
                    break;

                case 'expire':
                    Log::info("Pembayaran expired untuk order {$orderId}");
                    break;

                case 'cancel':
                    Log::info("Pembayaran dibatalkan untuk order {$orderId}");
                    break;

                default:
                    Log::info("Status lain untuk order {$orderId}: {$transactionStatus}");
                    break;
            }

            return response()->json(['message' => 'Callback handled']);
        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}
