<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\User;
use App\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminOrderCreateController extends Controller
{
    public function create()
    {
        $canteen = auth()->user()->canteen;

        $pelanggan = User::whereHas('orders', function ($q) use ($canteen) {
            $q->where('canteen_id', $canteen->id);
        })->orderBy('name')->get();

        $menus = $canteen->menus()->where('stok', '>', 0)->get();

        $statusOptions = [
            'pending'  => 'Pending',
            'diproses' => 'Diproses',
            'selesai'  => 'Selesai',
            'ditolak'  => 'Ditolak',
        ];

        return view('admin.orders.create', compact('pelanggan', 'menus', 'statusOptions'));
    }

    public function store(Request $request)
    {
        // ======== FIX: Filter dulu item yang qty > 0 sebelum validasi ========
        $rawItems = $request->input('items', []);
        $filteredItems = array_values(array_filter($rawItems, function ($item) {
            return isset($item['quantity']) && (int)$item['quantity'] > 0
                && isset($item['menu_id']) && !empty($item['menu_id']);
        }));

        // Merge items yang sudah difilter kembali ke request
        $request->merge(['items' => $filteredItems]);
        // =====================================================================

        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'payment_method'   => 'required|in:cash,balance',
            'status'           => 'required|in:pending,diproses,selesai,ditolak',
            'items'            => 'required|array|min:1',
            'items.*.menu_id'  => 'required|exists:produk,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'items.required'      => 'Pilih minimal 1 menu dengan jumlah lebih dari 0.',
            'items.min'           => 'Pilih minimal 1 menu dengan jumlah lebih dari 0.',
            'user_id.required'    => 'Pelanggan harus dipilih.',
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'status.required'     => 'Status pesanan harus dipilih.',
        ]);

        $canteen = auth()->user()->canteen;
        $user    = User::findOrFail($request->user_id);

        DB::beginTransaction();
        try {
            $total = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);

                if ($menu->canteen_id !== $canteen->id) {
                    throw new \Exception("Menu {$menu->name} bukan milik kantin ini.");
                }

                if (in_array($request->status, ['diproses', 'selesai'])) {
                    if (!$menu->isStokCukup($item['quantity'])) {
                        throw new \Exception("Stok {$menu->name} tidak mencukupi (sisa: {$menu->stok}).");
                    }
                }

                $price    = $menu->getDiscountedPrice();
                $subtotal = $price * $item['quantity'];
                $total   += $subtotal;

                $orderItems[] = [
                    'menu'     => $menu,
                    'quantity' => $item['quantity'],
                    'price'    => $price,
                    'subtotal' => $subtotal,
                ];
            }

            if ($request->payment_method === 'balance') {
                if (!$user->hasEnoughBalance($total)) {
                    throw new \Exception(
                        "Saldo {$user->name} tidak mencukupi. " .
                        "Saldo: Rp " . number_format($user->balance, 0, ',', '.') .
                        ", Kebutuhan: Rp " . number_format($total, 0, ',', '.')
                    );
                }
            }

            $paymentStatus = match(true) {
                $request->payment_method === 'balance' => 'paid',
                $request->payment_method === 'cash' && $request->status === 'selesai' => 'paid',
                default => 'unpaid',
            };

            $invoice = strtoupper($request->payment_method) . '-ADMIN-' . time() . '-' . $user->id;

            $order = Order::create([
                'user_id'        => $user->id,
                'canteen_id'     => $canteen->id,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'status'         => $request->status,
                'total_price'    => $total,
                'invoice'        => $invoice,
                'admin_deleted'  => in_array($request->status, ['selesai', 'ditolak']),
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id'       => $order->id,
                    'menu_id'        => $item['menu']->id,
                    'quantity'       => $item['quantity'],
                    'price'          => $item['price'],
                    'subtotal_price' => $item['subtotal'],
                ]);

                if (in_array($request->status, ['diproses', 'selesai'])) {
                    $item['menu']->kurangiStok($item['quantity']);
                }
            }

            if ($request->payment_method === 'balance') {
                $user->deductBalance(
                    $total,
                    "Pembayaran order admin #{$invoice}",
                    $order->id
                );
            }

            if ($request->status !== 'pending') {
                $log = $order->logs()->create([
                    'order_id'    => $order->id,
                    'canteen_id'  => $order->canteen_id,
                    'user_id'     => $order->user_id,
                    'status'      => $request->status,
                    'total_price' => $order->total_price,
                    'items'       => $order->items,
                ]);
                foreach ($order->items as $orderItem) {
                    $orderItem->update(['orderlog_id' => $log->id]);
                }
            }

            DB::commit();

            return redirect()->route('admin.orders.index')
                ->with('success', "Pesanan untuk {$user->name} berhasil dibuat! Total: Rp " . number_format($total, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin create order error: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function getUserInfo(User $user)
    {
        return response()->json([
            'name'              => $user->name,
            'email'             => $user->email,
            'balance'           => $user->balance,
            'formatted_balance' => 'Rp ' . number_format($user->balance, 0, ',', '.'),
        ]);
    }
}
