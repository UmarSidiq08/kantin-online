<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Menu;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class DiscountController extends Controller
{
    public function index()
    {
        $canteen = auth()->user()->canteen;
        $menus = $canteen->menus()->get();
        return view('admin.discounts.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);
        $menu = auth()->user()->canteen->menus()->findOrFail($request->menu_id);
        if ($data['type'] === 'percentage' && $data['value'] > 100) {
            return response()->json(['message' => 'Diskon persentase tidak boleh lebih dari 100%'], 422);
        }
        if ($data['type'] === 'fixed' && $data['value'] >= $menu->price) {
            return response()->json(['message' => 'Diskon nominal tidak boleh lebih besar atau sama dengan harga menu'], 422);
        }
        $existingDiscount = Discount::where('menu_id', $request->menu_id)->where('is_active', true)->where(function ($query) use ($data) {
            if (isset($data['start_date']) && isset($data['end_date'])) {
                $query->where(function ($q) use ($data) {
                    $q->whereBetween('start_date', [$data['start_date'], $data['end_date']])->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])->orWhere(function ($q2) use ($data) {
                        $q2->where('start_date', '<=', $data['start_date'])->where('end_date', '>=', $data['end_date']);
                    });
                });
            } else {
                $query->whereNull('start_date')->whereNull('end_date');
            }
        })->first();
        if ($existingDiscount) {
            return response()->json(['message' => 'Menu ini sudah memiliki diskon aktif untuk periode tersebut'], 422);
        }
        Discount::create($data);
        return response()->json(['message' => 'Diskon berhasil ditambahkan!']);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:discounts,id',
            'menu_id' => 'required|exists:menus,id',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);
        $discount = Discount::whereHas('menu', function ($query) {
            $query->where('canteen_id', auth()->user()->canteen->id);
        })->findOrFail($request->id);
        $menu = auth()->user()->canteen->menus()->findOrFail($request->menu_id);
        if ($data['type'] === 'percentage' && $data['value'] > 100) {
            return response()->json(['message' => 'Diskon persentase tidak boleh lebih dari 100%'], 422);
        }
        if ($data['type'] === 'fixed' && $data['value'] >= $menu->price) {
            return response()->json(['message' => 'Diskon nominal tidak boleh lebih besar atau sama dengan harga menu'], 422);
        }
        $discount->update($data);
        return response()->json(['message' => 'Diskon berhasil diperbarui!']);
    }

    public function destroy(Request $request)
    {
        $discount = Discount::whereHas('menu', function ($query) {
            $query->where('canteen_id', auth()->user()->canteen->id);
        })->findOrFail($request->id);
        $discount->delete();
        return response()->json(['message' => 'Diskon berhasil dihapus!']);
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Discount::with('menu')->whereHas('menu', function ($query) {
                $query->where('canteen_id', auth()->user()->canteen->id);
            })->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('admin.discounts.action', compact('row'))->render();
                })
                ->editColumn('menu_name', function ($row) {
                    return $row->menu->name;
                })
                ->editColumn('original_price', function ($row) {
                    return 'Rp ' . number_format($row->menu->price, 0, ',', '.');
                })
                ->editColumn('discount_value', function ($row) {
                    return $row->formatted_value;
                })
                ->editColumn('discounted_price', function ($row) {
                    $discountedPrice = $row->getPriceAfterDiscount($row->menu->price);
                    return 'Rp ' . number_format($discountedPrice, 0, ',', '.');
                })
                ->editColumn('period', function ($row) {
                    $period = '';
                    if ($row->start_date && $row->end_date) {
                        $period .= $row->start_date->format('d/m/Y') . ' - ' . $row->end_date->format('d/m/Y');
                    } elseif ($row->start_date) {
                        $period .= 'Mulai: ' . $row->start_date->format('d/m/Y');
                    } elseif ($row->end_date) {
                        $period .= 'Sampai: ' . $row->end_date->format('d/m/Y');
                    } else {
                        $period = 'Tidak Terbatas';
                    }
                    if ($row->start_time && $row->end_time) {
                        $period .= '<br><small class="text-muted">' . Carbon::parse($row->start_time)->format('H:i') . ' - ' . Carbon::parse($row->end_time)->format('H:i') . '</small>';
                    }
                    return $period;
                })
                ->editColumn('status', function ($row) {
                    $status = $row->status;
                    $badge = '';
                    switch ($status) {
                        case 'Berlaku Sekarang':
                            $badge = '<span class="badge bg-success">' . $status . '</span>';
                            break;
                        case 'Akan Berlaku':
                            $badge = '<span class="badge bg-warning">' . $status . '</span>';
                            break;
                        case 'Sudah Berakhir':
                            $badge = '<span class="badge bg-secondary">' . $status . '</span>';
                            break;
                        case 'Tidak Aktif':
                            $badge = '<span class="badge bg-danger">' . $status . '</span>';
                            break;
                        default:
                            $badge = '<span class="badge bg-secondary">' . $status . '</span>';
                    }
                    return $badge;
                })
                ->editColumn('savings', function ($row) {
                    if ($row->isValidNow()) {
                        $savings = $row->getDiscountAmount($row->menu->price);
                        $percentage = ($savings / $row->menu->price) * 100;
                        return '<strong class="text-success">Rp ' . number_format($savings, 0, ',', '.') . '</strong><br>' . '<small class="text-muted">(' . round($percentage, 1) . '% OFF)</small>';
                    }
                    return '<span class="text-muted">-</span>';
                })
                ->rawColumns(['action', 'period', 'status', 'savings'])
                ->make(true);
        }
    }

    public function toggleStatus(Request $request)
    {
        $discount = Discount::whereHas('menu', function ($query) {
            $query->where('canteen_id', auth()->user()->canteen->id);
        })->findOrFail($request->id);
        $discount->update(['is_active' => !$discount->is_active]);
        $status = $discount->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return response()->json(['message' => "Diskon berhasil {$status}!"]);
    }

    public function getMenuPrice(Request $request)
    {
        $menu = auth()->user()->canteen->menus()->findOrFail($request->menu_id);
        return response()->json([
            'price' => $menu->price,
            'formatted_price' => 'Rp ' . number_format($menu->price, 0, ',', '.')
        ]);
    }
}
