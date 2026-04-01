<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Constant;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public function index()
    {
        $canteen = auth()->user()->canteen;
        $menus = $canteen->menus()->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'stok' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu-images', 'public');
        }
        $data['canteen_id'] = auth()->user()->canteen->id;
        Menu::create($data);
        return response()->json(['message' => 'Menu berhasil disimpan!']);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:produk,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'stok' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $menu = auth()->user()->canteen->menus()->findOrFail($request->id);

        if ($request->hasFile('image')) {
            // Ada gambar baru → hapus gambar lama, simpan yang baru
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $data['image'] = $request->file('image')->store('menu-images', 'public');
        } else {
            // Tidak ada gambar baru → pertahankan gambar lama
            unset($data['image']);
        }

        $menu->update($data);
        return response()->json(['message' => 'Menu berhasil diperbarui!']);
    }

    public function destroy(Request $request)
    {
        $menu = auth()->user()->canteen->menus()->findOrFail($request->id);
        // Hapus gambar dari storage juga
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->delete();
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = auth()->user()->canteen->menus()->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', fn($row) => view('admin.menus.menu', compact('row'))->render())
                ->editColumn('price', fn($row) => 'Rp' . number_format($row->price, 0, ',', '.'))
                ->editColumn('image', function ($row) {
                    return $row->image ? '<img src="' . asset("storage/{$row->image}") . '" width="60">' : 'Tidak Ada';
                })
                ->editColumn('category', function ($menu) {
                    return Constant::MENU_CATEGORIES[$menu->category] ?? 'Tidak Diketahui';
                })
                ->addColumn('stok', function ($row) {
                    if ($row->stok == 0) {
                        return '<span class="badge bg-danger">Habis</span>';
                    } elseif ($row->stok <= 5) {
                        return '<span class="badge bg-warning text-dark">' . $row->stok . '</span>';
                    }
                    return '<span class="badge bg-success">' . $row->stok . '</span>';
                })
                ->rawColumns(['action', 'image', 'stok'])
                ->make(true);
        }
    }
}
