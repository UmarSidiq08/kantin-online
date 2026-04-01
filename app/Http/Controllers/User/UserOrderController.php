<?php

namespace App\Http\Controllers\User;

use App\Constant;
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

        $query = Menu::where('canteen_id', $canteenId)
            ->with(['discounts' => function ($discountQuery) {
                $discountQuery->active()
                    ->where(function ($q) {
                        $now = now();
                        $today = $now->toDateString();
                        $currentTime = $now->format('H:i:s');

                        $q->where(function ($dateQuery) use ($today) {
                            $dateQuery->whereNull('start_date')
                                ->orWhere('start_date', '<=', $today);
                        })
                            ->where(function ($dateQuery) use ($today) {
                                $dateQuery->whereNull('end_date')
                                    ->orWhere('end_date', '>=', $today);
                            })
                            ->where(function ($timeQuery) use ($currentTime) {
                                $timeQuery->whereNull('start_time')
                                    ->whereNull('end_time')
                                    ->orWhere(function ($tq) use ($currentTime) {
                                        $tq->whereNotNull('start_time')
                                            ->whereNotNull('end_time')
                                            ->where('start_time', '<=', $currentTime)
                                            ->where('end_time', '>=', $currentTime);
                                    });
                            });
                    });
            }]);

        if ($request->filled('category') && $request->category !== 'semua') {
            $query->where('category', $request->category);
        }

        $sort = $request->get('sort', 'default');
        $menus = $this->applySorting($query, $sort);
        $menus = $this->processMenuData($menus);

        return view('user.orders.index', [
            'menus' => $menus,
            'canteen' => Canteen::find($canteenId),
            'categoryOptions' => $this->getCategoryOptions(),
            'sortOptions' => $this->getSortOptions()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $canteenId = session('selected_canteen_id');
            if (!$canteenId) {
                return response()->json(['message' => 'Silakan pilih kantin terlebih dahulu.'], 400);
            }

            // ======== CEK STOK SEMUA MENU SEBELUM BUAT PESANAN ========
            $stokKurang = [];
            foreach ($validated['quantities'] as $menuId => $quantity) {
                if ($quantity > 0) {
                    $menu = Menu::findOrFail($menuId);
                    if (!$menu->isStokCukup($quantity)) {
                        $stokKurang[] = "{$menu->name} (stok tersisa: {$menu->stok})";
                    }
                }
            }

            // Jika ada menu yang stoknya kurang, batalkan semua
            if (!empty($stokKurang)) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Stok tidak mencukupi untuk: ' . implode(', ', $stokKurang),
                ], 422);
            }
            // ======================================================

            $order = Order::create([
                'user_id' => Auth::id(),
                'canteen_id' => $canteenId,
                'status' => Constant::ORDER_STATUS['PENDING'],
                'total_price' => 0
            ]);

            $total = 0;
            foreach ($validated['quantities'] as $menuId => $quantity) {
                if ($quantity > 0) {
                    $menu = Menu::findOrFail($menuId);
                    $price = $menu->hasActiveDiscount() ? $menu->discount_info['discounted_price'] : $menu->price;
                    $subtotal = $price * $quantity;
                    $total += $subtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $menuId,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal_price' => $subtotal
                    ]);
                }
            }

            $order->update(['total_price' => $total]);
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
            ->with(['items.menu', 'canteen'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($order) {
                $order->formatted_total_price = 'Rp ' . number_format($order->total_price, 0, ',', '.');
                $order->formatted_created_at = $order->created_at->format('d M Y, H:i');
                $order->status_display = ucfirst($order->status ?? '-');
                $order->payment_method_display = ucfirst($order->payment_method ?? '-');
                $order->payment_status_badge = $order->payment_status === 'paid' ? 'success' : 'warning';
                return $order;
            });

        return view('user.orders.history', [
            'orders' => $orders,
            'statusLabels' => Constant::ORDER_STATUS
        ]);
    }

    public function table(Request $request)
    {
        $orders = Order::with(['items.menu', 'canteen'])
            ->where('user_id', auth()->id())
            ->latest();

        return DataTables::of($orders)
            ->addIndexColumn()
            ->editColumn('created_at', fn($order) => $order->created_at->format('d M Y, H:i'))
            ->addColumn('canteen_name', fn($order) => $order->canteen ? $order->canteen->name : 'Unknown Canteen')
            ->addColumn('menus', function ($order) {
                return $order->items->map(function ($item) {
                    return $item->menu->name . ' x' . $item->quantity;
                })->implode('<br>');
            })
            ->addColumn('status', fn($order) => ucfirst($order->status ?? '-'))
            ->editColumn('total_price', fn($order) => 'Rp ' . number_format($order->total_price, 0, ',', '.'))
            ->addColumn('payment_method', fn($order) => ucfirst($order->payment_method ?? '-'))
            ->addColumn('payment_status', function ($order) {
                $badge = $order->payment_status === 'paid' ? 'success' : 'warning';
                return '<span class="badge bg-' . $badge . '">' . ucfirst($order->payment_status) . '</span>';
            })
            ->rawColumns(['status', 'menus', 'payment_status'])
            ->make(true);
    }

    private function getCategoryOptions()
    {
        return [
            'semua' => '🍽️ Semua Menu',
            'makanan' => '🍛 Makanan',
            'minuman' => '🥤 Minuman',
            'snack' => '🍿 Snack'
        ];
    }

    private function getSortOptions()
    {
        return [
            'default' => '📋 Default',
            'terpopuler' => '🔥 Terpopuler',
            'harga_rendah' => '💰 Harga Terendah',
            'harga_tinggi' => '💎 Harga Tertinggi',
            'nama_az' => '🔤 A-Z',
            'nama_za' => '🔤 Z-A'
        ];
    }

    private function processMenuData($menus)
    {
        $menuIds = $menus->pluck('id');
        $salesData = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($query) {
                $query->where('status', 'selesai');
            })
            ->whereIn('menu_id', $menuIds)
            ->groupBy('menu_id')
            ->pluck('total_sold', 'menu_id');

        return $menus->map(function ($menu) use ($salesData) {
            $menu->total_sold = $salesData[$menu->id] ?? 0;
            $menu->formatted_total_sold = number_format($menu->total_sold, 0, ',', '.');

            $rating = $menu->averageRating();
            $menu->full_stars = floor($rating);
            $menu->has_half_star = ($rating - $menu->full_stars) >= 0.5;
            $menu->empty_stars = 5 - $menu->full_stars - ($menu->has_half_star ? 1 : 0);
            $menu->formatted_average_rating = number_format($rating, 1);
            $menu->total_ratings = $menu->totalRatings();

            $menu->category_display = Constant::MENU_CATEGORIES[$menu->category] ?? 'Tidak Diketahui';

            $menu->has_active_discount = $menu->hasActiveDiscount();

            if ($menu->has_active_discount) {
                $activeDiscount = $menu->activeDiscount();
                $discountedPrice = $menu->getDiscountedPrice();
                $discountAmount = $menu->getDiscountAmount();
                $discountPercentage = round($menu->getDiscountPercentage());

                $menu->discount_percentage = $discountPercentage;
                $menu->formatted_original_price = 'Rp ' . number_format($menu->price, 0, ',', '.');
                $menu->formatted_discounted_price = 'Rp ' . number_format($discountedPrice, 0, ',', '.');
                $menu->formatted_savings = 'Rp ' . number_format($discountAmount, 0, ',', '.');

                $periodText = '';
                if ($activeDiscount->end_date) {
                    $periodText .= 's/d ' . $activeDiscount->end_date->format('d/m/Y');
                }
                if ($activeDiscount->end_time) {
                    $periodText .= ' ' . $activeDiscount->end_time->format('H:i');
                }
                $menu->discount_period = $periodText;
            }

            $menu->price_display = 'Rp ' . number_format($menu->price, 0, ',', '.');

            // ======== TAMBAHAN INFO STOK ========
            $menu->stok_tersedia = $menu->isStokTersedia();
            $menu->stok_display = $menu->stok > 0 ? "Sisa {$menu->stok}" : 'Stok Habis';
            // =====================================

            return $menu;
        });
    }

    private function applySorting($query, $sort)
    {
        switch ($sort) {
            case 'terpopuler':
                $menus = $query->get();
                $salesData = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
                    ->whereHas('order', function ($orderQuery) {
                        $orderQuery->where('status', 'selesai');
                    })
                    ->whereIn('menu_id', $menus->pluck('id'))
                    ->groupBy('menu_id')
                    ->pluck('total_sold', 'menu_id');

                return $menus->map(function ($menu) use ($salesData) {
                    $menu->total_sold = $salesData[$menu->id] ?? 0;
                    return $menu;
                })->sortByDesc('total_sold')->values();

            case 'harga_rendah':
                return $query->orderBy('price', 'asc')->get();

            case 'harga_tinggi':
                return $query->orderBy('price', 'desc')->get();

            case 'nama_az':
                return $query->orderBy('name', 'asc')->get();

            case 'nama_za':
                return $query->orderBy('name', 'desc')->get();

            default:
                return $query->orderBy('created_at', 'desc')->get();
        }
    }
}
