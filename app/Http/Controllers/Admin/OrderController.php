<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderLog;
use App\Constant;
use Illuminate\Support\Facades\log;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar pesanan yang belum disembunyikan admin.
     */
    public function index(Request $request)
    {
        $canteenId = auth()->user()->canteen->id;

        $ordersQuery = Order::with('user', 'items.menu')
            ->where('canteen_id', $canteenId)
            ->where('admin_deleted', false);

        // Tambahkan filter jika ada status
        if ($request->has('status') && $request->status !== '') {
            $ordersQuery->where('status', $request->status);
        }

        $orders = $ordersQuery->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }


    /**
     * Tandai pesanan sebagai DIPROSES.
     */
    public function markAsProcessed(Order $order)
    {
        $order->update([
            'status' => Constant::ORDER_STATUS['DIPROSES'],
        ]);

        // 1. Buat log
        $log = $order->logs()->create([
            'order_id'    => $order->id,
            'canteen_id'  => $order->canteen_id,
            'user_id'     => $order->user_id,
            'status'      => Constant::ORDER_STATUS['DIPROSES'],
            'total_price' => $order->total_price,
            'items'       => $order->items, // json/array
        ]);

        // 2. Update semua item pesanan agar punya orderlog_id
        foreach ($order->items as $item) {
            $item->update(['orderlog_id' => $log->id]);
        }

        return back()->with('success', 'Pesanan berhasil ditandai sebagai diproses.');
    }

    public function markProcessedCash(Order $order)
    {
        $order->update([
            // Jangan ubah payment_status di sini
            'status' => Constant::ORDER_STATUS['DIPROSES'],
        ]);

        $log = $order->logs()->create([
            'order_id'    => $order->id,
            'canteen_id'  => $order->canteen_id,
            'user_id'     => $order->user_id,
            'status'      => Constant::ORDER_STATUS['DIPROSES'],
            'total_price' => $order->total_price,
            'items'       => $order->items,
        ]);

        foreach ($order->items as $item) {
            $item->update(['orderlog_id' => $log->id]);
        }

        return redirect()->back()->with('success', 'Pesanan cash diproses. Tunggu konfirmasi pembayaran.');
    }


    /**
     * Tandai pesanan sebagai DITOLAK dan sembunyikan dari admin.
     */
    public function markAsRejected(Order $order)
    {
        $order->update([
            'status' => Constant::ORDER_STATUS['DITOLAK'],
            'admin_deleted' => false,
        ]);

        $log = $order->logs()->create([
            'order_id'    => $order->id,
            'canteen_id'  => $order->canteen_id,
            'user_id'     => $order->user_id,
            'status'      => Constant::ORDER_STATUS['DITOLAK'],
            'total_price' => $order->total_price,
            'items'       => $order->items,
        ]);

        foreach ($order->items as $item) {
            $item->update(['orderlog_id' => $log->id]);
        }

        return back()->with('success', 'Pesanan berhasil ditolak.');
    }


    /**
     * Tandai pesanan sebagai SELESAI dan sembunyikan dari admin.
     */
    public function markAsCompleted(Order $order)
    {
        $order->update([
            'status' => Constant::ORDER_STATUS['SELESAI'],
            'admin_deleted' => true,
        ]);

        $log = $order->logs()->create([
            'order_id'    => $order->id,
            'canteen_id'  => $order->canteen_id,
            'user_id'     => $order->user_id,
            'status'      => Constant::ORDER_STATUS['SELESAI'],
            'total_price' => $order->total_price,
            'items'       => $order->items,
        ]);

        foreach ($order->items as $item) {
            $item->update(['orderlog_id' => $log->id]);
        }

        return back()->with('success', 'Pesanan ditandai selesai dan disembunyikan dari daftar.');
    }


    /**
     * Sembunyikan pesanan DITOLAK secara manual dari daftar admin.
     */
    public function deleteRejected(Order $order)
    {
        if ($order->status === Constant::ORDER_STATUS['DITOLAK']) {
            $order->update(['admin_deleted' => true]);
        }

        return back()->with('success', 'Pesanan ditolak telah dihapus dari daftar admin.');
    }

    /**
     * Tampilkan riwayat log perubahan status pesanan.
     */
    public function logHistory(Request $request)
    {
        $canteenId = auth()->user()->canteen->id;

        $query = OrderLog::whereHas('order', function ($q) use ($canteenId) {
            $q->where('canteen_id', $canteenId);
        })->with('order');

        // Filter berdasarkan status (jika ada)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan nama user (jika ada)
        if ($request->has('user') && $request->user != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        $logs = $query->latest()->get();

        return view('admin.logs.index', compact('logs'));
    }


    /**
     * Sembunyikan pesanan dari daftar admin (umum).
     */
    public function destroy(Order $order)
    {
        $order->update(['admin_deleted' => true]);

        return back()->with('success', 'Pesanan berhasil disembunyikan dari daftar admin.');
    }
    public function confirmCashPayment(Order $order)
    {
        $order->update([
            'payment_status' => Constant::PAYMENT_STATUS['PAID'],
        ]);

        return redirect()->back()->with('success', 'Pembayaran tunai dikonfirmasi.');
    }
}
