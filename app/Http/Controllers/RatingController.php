<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Order;
use App\Models\Menu;


class RatingController extends Controller
{
    public function show(Menu $menu)
    {
        // Load reviews dengan relasi user dan order untuk mendapatkan informasi lengkap
        $menu->load([
            'ratings' => function($query) {
                $query->with(['user', 'order'])
                      ->orderBy('created_at', 'desc');
            }
        ]);

        return view('user.menus.reviews', compact('menu'));
    }
     public function store(Request $request) {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:500'
        ]);

        // Cek order punya user dan ada menu nya (sesuai struktur kamu)
        $orderExists = Order::where('id', $validated['order_id'])
            ->where('user_id', auth()->id())
            ->whereHas('items', function($q) use ($validated) { // 'items' sesuai model Order kamu
                $q->where('menu_id', $validated['menu_id']);
            })->exists();

        if (!$orderExists) {
            return response()->json(['error' => 'Anda belum pernah memesan menu ini'], 403);
        }

        // Cek sudah rating belum
        if (Rating::where('user_id', auth()->id())->where('menu_id', $validated['menu_id'])->exists()) {
            return response()->json(['error' => 'Anda sudah memberikan rating untuk menu ini'], 403);
        }

        Rating::create([
            'menu_id' => $validated['menu_id'],
            'user_id' => auth()->id(),
            'order_id' => $validated['order_id'],
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text']
        ]);

        return response()->json(['message' => 'Rating berhasil disimpan']);
    }
}
