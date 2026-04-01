<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderLog;
use App\Constant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $canteenId = auth()->user()->canteen->id;
        $ordersQuery = Order::with('user', 'items.menu')->where('canteen_id', $canteenId)->where('admin_deleted', false);
        if ($request->has('status') && $request->status !== '') {
            $ordersQuery->where('status', $request->status);
        }
        $orders = $ordersQuery->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function bulkAcceptAll()
    {
        $canteenId = auth()->user()->canteen->id;
        try {
            DB::beginTransaction();
            $pendingOrders = Order::where('canteen_id', $canteenId)
                ->where('status', Constant::ORDER_STATUS['PENDING'])
                ->where('admin_deleted', false)
                ->with('items.menu')
                ->get();

            if ($pendingOrders->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada pesanan pending']);
            }

            $processedCount = 0;
            foreach ($pendingOrders as $order) {
                // ======== KURANGI STOK SETIAP ITEM ========
                foreach ($order->items as $item) {
                    $menu = $item->menu;
                    if (!$menu->isStokCukup($item->quantity)) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Stok {$menu->name} tidak mencukupi (sisa: {$menu->stok}). Pesanan #{$order->id} gagal diproses."
                        ], 422);
                    }
                    $menu->kurangiStok($item->quantity);
                }
                // ==========================================

                $order->update(['status' => Constant::ORDER_STATUS['DIPROSES']]);
                $log = $order->logs()->create([
                    'order_id' => $order->id,
                    'canteen_id' => $order->canteen_id,
                    'user_id' => $order->user_id,
                    'status' => Constant::ORDER_STATUS['DIPROSES'],
                    'total_price' => $order->total_price,
                    'items' => $order->items
                ]);
                foreach ($order->items as $item) {
                    $item->update(['orderlog_id' => $log->id]);
                }
                $processedCount++;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Berhasil menerima {$processedCount} pesanan",
                'processed_count' => $processedCount
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk accept error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memproses pesanan'], 500);
        }
    }

    public function bulkRejectAll()
    {
        $canteenId = auth()->user()->canteen->id;
        try {
            DB::beginTransaction();
            $pendingOrders = Order::where('canteen_id', $canteenId)
                ->where('status', Constant::ORDER_STATUS['PENDING'])
                ->where('admin_deleted', false)
                ->get();

            if ($pendingOrders->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada pesanan pending']);
            }

            $processedCount = 0;
            foreach ($pendingOrders as $order) {
                // Stok TIDAK perlu dikembalikan karena belum dipotong saat user pesan
                $order->update(['status' => Constant::ORDER_STATUS['DITOLAK'], 'admin_deleted' => false]);
                $log = $order->logs()->create([
                    'order_id' => $order->id,
                    'canteen_id' => $order->canteen_id,
                    'user_id' => $order->user_id,
                    'status' => Constant::ORDER_STATUS['DITOLAK'],
                    'total_price' => $order->total_price,
                    'items' => $order->items
                ]);
                foreach ($order->items as $item) {
                    $item->update(['orderlog_id' => $log->id]);
                }
                $processedCount++;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Berhasil menolak {$processedCount} pesanan",
                'processed_count' => $processedCount
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk reject error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memproses pesanan'], 500);
        }
    }

    public function markAsProcessed(Order $order)
    {
        try {
            DB::beginTransaction();

            // ======== KURANGI STOK SETIAP ITEM ========
            $order->load('items.menu');
            foreach ($order->items as $item) {
                $menu = $item->menu;
                if (!$menu->isStokCukup($item->quantity)) {
                    DB::rollBack();
                    return back()->with('error', "Stok {$menu->name} tidak mencukupi (sisa: {$menu->stok}). Pesanan gagal diterima.");
                }
                $menu->kurangiStok($item->quantity);
            }
            // ==========================================

            $order->update(['status' => Constant::ORDER_STATUS['DIPROSES']]);
            $log = $order->logs()->create([
                'order_id' => $order->id,
                'canteen_id' => $order->canteen_id,
                'user_id' => $order->user_id,
                'status' => Constant::ORDER_STATUS['DIPROSES'],
                'total_price' => $order->total_price,
                'items' => $order->items
            ]);
            foreach ($order->items as $item) {
                $item->update(['orderlog_id' => $log->id]);
            }

            DB::commit();
            return back()->with('success', 'Pesanan berhasil ditandai sebagai diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('markAsProcessed error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function markProcessedCash(Order $order)
    {
        try {
            DB::beginTransaction();

            // ======== KURANGI STOK SETIAP ITEM ========
            $order->load('items.menu');
            foreach ($order->items as $item) {
                $menu = $item->menu;
                if (!$menu->isStokCukup($item->quantity)) {
                    DB::rollBack();
                    return back()->with('error', "Stok {$menu->name} tidak mencukupi (sisa: {$menu->stok}). Pesanan gagal diterima.");
                }
                $menu->kurangiStok($item->quantity);
            }
            // ==========================================

            $order->update(['status' => Constant::ORDER_STATUS['DIPROSES']]);
            $log = $order->logs()->create([
                'order_id' => $order->id,
                'canteen_id' => $order->canteen_id,
                'user_id' => $order->user_id,
                'status' => Constant::ORDER_STATUS['DIPROSES'],
                'total_price' => $order->total_price,
                'items' => $order->items
            ]);
            foreach ($order->items as $item) {
                $item->update(['orderlog_id' => $log->id]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pesanan cash diproses. Tunggu konfirmasi pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('markProcessedCash error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function markAsRejected(Order $order)
    {
        // Stok TIDAK perlu dikembalikan karena belum dipotong saat user pesan
        $order->update(['status' => Constant::ORDER_STATUS['DITOLAK'], 'admin_deleted' => false]);
        $log = $order->logs()->create([
            'order_id' => $order->id,
            'canteen_id' => $order->canteen_id,
            'user_id' => $order->user_id,
            'status' => Constant::ORDER_STATUS['DITOLAK'],
            'total_price' => $order->total_price,
            'items' => $order->items
        ]);
        foreach ($order->items as $item) {
            $item->update(['orderlog_id' => $log->id]);
        }
        return back()->with('success', 'Pesanan berhasil ditolak.');
    }

    public function markAsCompleted(Order $order)
    {
        // Stok sudah terpotong saat DIPROSES, tidak perlu ubah lagi
        $updateData = ['status' => Constant::ORDER_STATUS['SELESAI'], 'admin_deleted' => true];
        if ($order->payment_method === 'cash' && $order->payment_status === 'unpaid') {
            $updateData['payment_status'] = 'paid';
        }
        $order->update($updateData);
        $log = $order->logs()->create([
            'order_id' => $order->id,
            'canteen_id' => $order->canteen_id,
            'user_id' => $order->user_id,
            'status' => Constant::ORDER_STATUS['SELESAI'],
            'total_price' => $order->total_price,
            'items' => $order->items
        ]);
        foreach ($order->items as $item) {
            $item->update(['orderlog_id' => $log->id]);
        }
        return response()->json(['success' => true, 'message' => 'Pesanan ditandai selesai']);
    }

    public function deleteRejected(Order $order)
    {
        if ($order->status === Constant::ORDER_STATUS['DITOLAK']) {
            $order->update(['admin_deleted' => true]);
        }
        return back()->with('success', 'Pesanan ditolak telah dihapus dari daftar admin.');
    }

    public function logHistory(Request $request)
    {
        $canteenId = auth()->user()->canteen->id;
        $query = OrderLog::whereHas('order', function ($q) use ($canteenId) {
            $q->where('canteen_id', $canteenId);
        })->with('order');
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('user') && $request->user != '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }
        $logs = $query->latest()->get();
        return view('admin.logs.index', compact('logs'));
    }

    public function destroy(Order $order)
    {
        $order->update(['admin_deleted' => true]);
        return back()->with('success', 'Pesanan berhasil disembunyikan dari daftar admin.');
    }

    public function confirmCashPayment(Order $order)
    {
        $order->update(['payment_status' => Constant::PAYMENT_STATUS['PAID']]);
        return redirect()->back()->with('success', 'Pembayaran tunai dikonfirmasi.');
    }
}
