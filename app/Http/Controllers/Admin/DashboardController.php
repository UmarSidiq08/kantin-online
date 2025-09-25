<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $canteenId = Auth::user()->canteen->id;

        // Total orders
        $totalOrders = Order::where('canteen_id', $canteenId)->count();

        // Total revenue (selesai orders only)
        $totalRevenue = Order::where('canteen_id', $canteenId)
            ->where('status', 'selesai')
            ->sum('total_price');

        // Revenue breakdown by payment method
        $revenueByPaymentMethod = Order::where('canteen_id', $canteenId)
            ->where('status', 'selesai')
            ->select('payment_method', DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method');

        // Extract individual payment method revenues
        $cashRevenue = $revenueByPaymentMethod->get('cash')->total_revenue ?? 0;
        $digitalRevenue = $revenueByPaymentMethod->get('digital')->total_revenue ?? 0;
        $balanceRevenue = $revenueByPaymentMethod->get('balance')->total_revenue ?? 0;

        // Top menus
        $topMenus = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('order', function ($query) use ($canteenId) {
                $query->where('canteen_id', $canteenId)
                    ->where('status', 'selesai');
            })
            ->groupBy('menu_id')
            ->with('menu')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        $menuNames = $topMenus->pluck('menu.name');
        $menuQuantities = $topMenus->pluck('total_qty');

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'cashRevenue',
            'digitalRevenue',
            'balanceRevenue',
            'topMenus',
            'menuNames',
            'menuQuantities'
        ));
    }
}
