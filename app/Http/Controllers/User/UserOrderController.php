<?php

namespace App\Http\Controllers\User;

use App\Constant;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\Canteen;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserOrderController extends Controller
{
    public function index(Request $request)
    {
        $canteenId = session('selected_canteen_id');
        if (!$canteenId) {
            return redirect()->route('user.dashboard')->with('error', 'Silakan pilih kantin terlebih dahulu.');
        }

        $query = Menu::where('canteen_id', $canteenId);

        if ($request->filled('category') && $request->category !== 'semua') {
            $query->where('category', $request->category);
        }

        $menus = $query->get();
        $canteen = Canteen::find($canteenId);

        return view('user.orders.index', compact('menus', 'canteen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:menus,id',
            'menus.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat order
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => Constant::ORDER_STATUS['PENDING'],
                'total_price' => 0
            ]);

            $total = 0;

            // 2. Tambahkan setiap item ke order
            foreach ($request->menus as $item) {
                $menu = Menu::find($item['id']);
                $subtotal = $menu->price * $item['quantity'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'subtotal_price' => $subtotal
                ]);
            }
            $order->update([
                'total_price' => $total
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dibuat!',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('user.orders.history', [
            'orders' => $orders,
            'statusLabels' => Constant::ORDER_STATUS
        ]);
    }

    public function table(Request $request)
    {
        $orders = Order::with('items.menu')->where('user_id', auth()->id())->latest();


        return DataTables::of($orders)
            ->addIndexColumn()
            ->editColumn('created_at', fn($order) => $order->created_at->format('d M Y, H:i'))
            ->addColumn('menus', function ($order) {
                // Gabungkan semua nama menu yang dipesan
                return $order->items->map(function ($item) {
                    return $item->menu->name . ' x' . $item->quantity;
                })->implode('<br>');
            })
            ->addColumn('status', fn($log) => ucfirst($log->status ?? '-'))
            ->editColumn('total_price', fn($order) => 'Rp ' . number_format($order->total_price, 0, ',', '.'))
            ->addColumn('payment_method', function ($order) {
                return ucfirst($order->payment_method ?? '-');
            })
            ->addColumn('payment_status', function ($order) {
                $badge = $order->payment_status === 'paid' ? 'success' : 'warning';
                return '<span class="badge bg-' . $badge . '">' . ucfirst($order->payment_status) . '</span>';
            })
            ->rawColumns(['status', 'menus', 'payment_status'])
            ->make(true);
    }
}
