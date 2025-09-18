<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['menu.canteen', 'menu.discounts' => function($query) {
                $query->where('is_active', true);
            }])
            ->where('user_id', Auth::id())
            ->get()
            ->groupBy('canteen_id');

        $cartData = $this->prepareCartData($carts);

        return view('user.cart.index', $cartData);
    }

    private function prepareCartData($carts)
    {
        $cartsData = [];
        $grandTotal = 0;
        $grandOriginalTotal = 0;

        foreach ($carts as $canteenId => $canteenCarts) {
            $canteenTotal = $canteenCarts->sum('total_price');
            $canteenOriginalTotal = $canteenCarts->sum(function($cart) {
                return $cart->menu->price * $cart->quantity;
            });
            $canteenSavings = $canteenOriginalTotal - $canteenTotal;

            $cartsData[$canteenId] = [
                'items' => $canteenCarts,
                'canteen_name' => $canteenCarts->first()->menu->canteen->name,
                'total' => $canteenTotal,
                'original_total' => $canteenOriginalTotal,
                'savings' => $canteenSavings,
                'count' => $canteenCarts->count()
            ];

            $grandTotal += $canteenTotal;
            $grandOriginalTotal += $canteenOriginalTotal;
        }

        return [
            'carts' => $carts,
            'cartsData' => $cartsData,
            'grandTotal' => $grandTotal,
            'grandOriginalTotal' => $grandOriginalTotal,
            'grandSavings' => $grandOriginalTotal - $grandTotal,
            'userBalance' => Auth::user()->balance
        ];
    }

    public function add(Request $request)
    {
        $quantities = $request->input('quantities');
        $userId = auth()->id();

        if (!is_array($quantities) || empty($quantities)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal satu menu terlebih dahulu.');
        }

        foreach ($quantities as $menuId => $qty) {
            if ($qty > 0) {
                $menu = Menu::findOrFail($menuId);
                Cart::updateOrCreate(
                    ['user_id' => $userId, 'menu_id' => $menuId],
                    ['quantity' => DB::raw("quantity + $qty"), 'canteen_id' => $menu->canteen_id]
                );
            }
        }

        return redirect()->route('user.cart.index')->with('success', 'Menu ditambahkan ke keranjang!');
    }

    public function destroy($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cart->delete();

        return response()->json(['message' => 'Item berhasil dihapus dari keranjang!']);
    }

    public function checkoutCanteen($canteenId, Request $request)
    {
        $selectedMethod = $request->input('payment_method', 'cash');

        $cartItems = Cart::with(['menu', 'menu.discounts' => function($query) {
                $query->where('is_active', true);
            }])
            ->where('user_id', Auth::id())
            ->where('canteen_id', $canteenId)
            ->get();

        if ($cartItems->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada item di keranjang untuk kantin ini.'], 400);
            }
            return redirect()->route('user.cart.index')->with('error', 'Tidak ada item di keranjang untuk kantin ini.');
        }

        return redirect()->route('user.checkout.canteen', ['canteenId' => $canteenId, 'payment_method' => $selectedMethod]);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $cart = Cart::with(['menu', 'menu.discounts' => function($query) {
                $query->where('is_active', true);
            }])
            ->where('id', $request->cart_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $oldQuantity = $cart->quantity;
        $cart->update(['quantity' => $request->quantity]);
        $cart->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Jumlah item berhasil diperbarui',
            'data' => [
                'old_quantity' => $oldQuantity,
                'new_quantity' => $cart->quantity,
                'unit_price' => $cart->menu->price,
                'discounted_unit_price' => $cart->discounted_price,
                'total_price' => $cart->total_price,
                'total_savings' => $cart->total_savings,
                'formatted_total' => 'Rp ' . number_format($cart->total_price, 0, ',', '.'),
                'has_discount' => $cart->hasActiveDiscount()
            ]
        ]);
    }

    public function getCartSummary($canteenId = null)
    {
        $query = Cart::with(['menu', 'menu.discounts' => function($q) {
                $q->where('is_active', true);
            }])
            ->where('user_id', Auth::id());

        if ($canteenId) {
            $query->where('canteen_id', $canteenId);
        }

        $cartItems = $query->get();

        $summary = [
            'total_items' => $cartItems->sum('quantity'),
            'original_total' => $cartItems->sum(function($cart) {
                return $cart->menu->price * $cart->quantity;
            }),
            'discounted_total' => $cartItems->sum('total_price'),
            'total_savings' => $cartItems->sum('total_savings'),
            'items' => $cartItems->map(function($cart) {
                return [
                    'id' => $cart->id,
                    'menu_id' => $cart->menu_id,
                    'menu_name' => $cart->menu->name,
                    'quantity' => $cart->quantity,
                    'unit_price' => $cart->menu->price,
                    'discounted_unit_price' => $cart->discounted_price,
                    'total_price' => $cart->total_price,
                    'savings' => $cart->total_savings,
                    'has_discount' => $cart->hasActiveDiscount(),
                    'discount_info' => $cart->hasActiveDiscount() ? [
                        'type' => $cart->active_discount->type,
                        'value' => $cart->active_discount->value,
                        'formatted_value' => $cart->active_discount->formatted_value
                    ] : null
                ];
            })
        ];

        return response()->json($summary);
    }

    public function clearCanteenCart($canteenId)
    {
        Cart::where('user_id', Auth::id())
            ->where('canteen_id', $canteenId)
            ->delete();

        return response()->json(['message' => 'Cart cleared successfully']);
    }

    public function getCartCount()
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}
