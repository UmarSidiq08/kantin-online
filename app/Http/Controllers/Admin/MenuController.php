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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $canteen = auth()->user()->canteen;

        $menus = $canteen->menus()->get();

        return view('admin.menus.index', compact('menus'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
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
            'id' => 'required|exists:menus,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $menu = auth()->user()->canteen->menus()->findOrFail($request->id);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu-images', 'public');
        }

        $menu->update($data);

        return response()->json(['message' => 'Menu berhasil diperbarui!']);
    }



    /**
     * Remove the specified resource from storage.
     */ public function destroy(Request $request)
    {
        $menu = auth()->user()->canteen->menus()->findOrFail($request->id);
        $menu->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus!'
        ]);
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
                    return $row->image
                        ? '<img src="' . asset("storage/{$row->image}") . '" width="60">'
                        : 'Tidak Ada';
                })
                ->editColumn('category', function ($menu) {
                    return Constant::MENU_CATEGORIES[$menu->category] ?? 'Tidak Diketahui';
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
    }
}
