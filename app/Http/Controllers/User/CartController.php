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
        // Ambil cart dengan relasi menu dan canteen, dikelompokkan berdasarkan canteen
        $carts = Cart::with(['menu.canteen'])
            ->where('user_id', Auth::id())
            ->get()
            ->groupBy('canteen_id');

        return view('user.cart.index', compact('carts'));
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
                    [
                        'user_id' => $userId,
                        'menu_id' => $menuId,
                    ],
                    [
                        'quantity' => DB::raw("quantity + $qty"),
                        'canteen_id' => $menu->canteen_id,
                    ]
                );
            }
        }

        return redirect()->route('user.cart.index')->with('success', 'Menu ditambahkan ke keranjang!');
    }

    public function destroy($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cart->delete();

        return redirect()->route('user.cart.index')->with('success', 'Item berhasil dihapus dari keranjang!');
    }

    // Method baru untuk checkout per kantin
    public function checkoutCanteen($canteenId, Request $request)
    {
        $selectedMethod = $request->input('payment_method', 'cash');

        // Validasi cart items untuk kantin ini
        $cartItems = Cart::with('menu')
            ->where('user_id', Auth::id())
            ->where('canteen_id', $canteenId)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada item di keranjang untuk kantin ini.'
            ], 400);
        }

        // Redirect ke checkout controller dengan parameter kantin
        return redirect()->route('user.checkout.canteen', [
            'canteenId' => $canteenId,
            'payment_method' => $selectedMethod
        ]);
    }
}
