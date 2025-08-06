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
        $totalOrders = Order::where('canteen_id', $canteenId)->count();
        $totalRevenue = Order::where('canteen_id', $canteenId)
            ->where('status', 'selesai')
            ->sum('total_price');

        $topMenus = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('order', function ($query) use ($canteenId) {
                $query->where('canteen_id', $canteenId)
                    ->where('status', 'selesai');
            })
            ->groupBy('menu_id')
            ->with('menu')
            ->inRandomOrder()
            ->take(5)
            ->get();
            
        $menuNames = $topMenus->pluck('menu.name');
        $menuQuantities = $topMenus->pluck('total_qty');
        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'topMenus',
            'menuNames',
            'menuQuantities'
        ));
    }
}
