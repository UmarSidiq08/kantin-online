<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\CanteenBlock;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Daftar semua pelanggan yang pernah order di kantin ini
     */
    public function index()
    {
        $canteen = auth()->user()->canteen;

        // Ambil user yang pernah order di kantin ini
        $pelanggan = User::whereHas('orders', function ($q) use ($canteen) {
                $q->where('canteen_id', $canteen->id);
            })
            ->withCount(['orders as total_order' => function ($q) use ($canteen) {
                $q->where('canteen_id', $canteen->id);
            }])
            ->get()
            ->map(function ($user) use ($canteen) {
                $user->is_blocked = $canteen->isUserBlocked($user->id);
                return $user;
            });

        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    /**
     * Detail user: info + riwayat pesanan di kantin ini
     */
    public function show(User $user)
    {
        $canteen = auth()->user()->canteen;

        // Pastikan user ini pernah order di kantin kita
        $pernahOrder = Order::where('user_id', $user->id)
            ->where('canteen_id', $canteen->id)
            ->exists();

        if (!$pernahOrder) {
            return redirect()->route('admin.pelanggan.index')
                ->with('error', 'Pelanggan ini tidak ditemukan di kantin Anda.');
        }

        $riwayatPesanan = Order::where('user_id', $user->id)
            ->where('canteen_id', $canteen->id)
            ->with('items.menu')
            ->latest()
            ->get();

        $isBlocked = $canteen->isUserBlocked($user->id);

        return view('admin.pelanggan.show', compact('user', 'riwayatPesanan', 'isBlocked'));
    }

    /**
     * Toggle blokir / aktifkan user
     */
    public function toggleBlock(User $user)
    {
        $canteen = auth()->user()->canteen;

        if ($canteen->isUserBlocked($user->id)) {
            $canteen->unblockUser($user->id);
            $message = "{$user->name} berhasil diaktifkan kembali.";
        } else {
            $canteen->blockUser($user->id);
            $message = "{$user->name} berhasil diblokir.";
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
