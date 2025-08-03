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
        $carts = Cart::with('menu')->where('user_id', Auth::id())->get();
        return view('user.cart.index', compact('carts'));
    }

    // CartController.php

    public function add(Request $request)
    {
        $quantities = $request->input('quantities');
        $userId = auth()->id();

        // Cek apakah sudah ada item di keranjang
        $existingCart = Cart::where('user_id', $userId)->first();

        // Ambil salah satu menu yang akan ditambahkan
        $quantities = $request->input('quantities');

        if (!is_array($quantities) || empty($quantities)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal satu menu terlebih dahulu.');
        }

        $firstMenuId = array_key_first($quantities);

        $menu = Menu::findOrFail($firstMenuId);
        $menuCanteenId = $menu->canteen_id;

        // Kalau sudah ada item dan kantinnya beda → tolak
        if ($existingCart && $existingCart->canteen_id !== $menuCanteenId) {
            return redirect()->route('user.cart.index')->with('error', 'Kamu hanya bisa memesan dari satu kantin dalam satu waktu. Kosongkan keranjang terlebih dahulu.');
        }

        // Tambahkan atau update item ke keranjang
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
}
