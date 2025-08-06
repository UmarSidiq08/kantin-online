<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\MenuSalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function data(Request $request)
    {
        $canteenId = Auth::user()->canteen->id;
        $start = $request->start_date;
        $end = $request->end_date;
        $sortBy = $request->sort_by;
        $query = OrderItem::with('menu')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select(
                'order_items.menu_id',
                'menus.name as menu_name',
                DB::raw('SUM(order_items.quantity) as total_terjual'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_pendapatan'),
                DB::raw('MAX(order_items.created_at) as terakhir_terjual')
            )
            ->whereHas('order', fn($q) => $q->where('status', 'selesai'))
            ->where('menus.canteen_id', $canteenId)
            ->groupBy('order_items.menu_id', 'menus.name');

        if ($start && $end) {
            $query->whereBetween(DB::raw('DATE(order_items.created_at)'), [$start, $end]);
        }
        switch ($sortBy) {
            case 'total_terjual_desc':
                $query->orderBy('total_terjual', 'desc');
                break;
            case 'total_terjual_asc':
                $query->orderBy('total_terjual', 'asc');
                break;
            case 'total_pendapatan_desc':
                $query->orderBy('total_pendapatan', 'desc');
                break;
            case 'total_pendapatan_asc':
                $query->orderBy('total_pendapatan', 'asc');
                break;
            default:
                // Default order if needed
                $query->orderBy('terakhir_terjual', 'desc');
                break;
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('menu', fn($item) => $item->menu_name ?? '-')
            ->addColumn('jumlah', fn($item) => $item->total_terjual)
            ->addColumn('total', fn($item) => 'Rp ' . number_format($item->total_pendapatan, 0, ',', '.'))
            ->addColumn('terakhir', fn($item) => \Carbon\Carbon::parse($item->terakhir_terjual)->format('Y-m-d H:i'))

            // Ini bagian penting untuk filter berdasarkan nama menu:
            ->filterColumn('menu_name', function ($query, $keyword) {
                $query->where('menus.name', 'like', "%{$keyword}%");
            })

            ->toJson();
    }

    public function exportExcel(Request $request)
    {
        $canteenId = auth()->user()->canteen->id;
        $start = $request->start_date;
        $end = $request->end_date;

        return Excel::download(new MenuSalesExport($start, $end, $canteenId), 'laporan_penjualan_menu.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $canteenId = auth()->user()->canteen->id;
        $start = $request->start_date;
        $end = $request->end_date;

        $query = OrderItem::join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select(
                'menus.name as menu_name',
                DB::raw('SUM(order_items.quantity) as total_terjual'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_pendapatan'),
                DB::raw('MAX(order_items.created_at) as terakhir_terjual')
            )
            ->whereHas('order', fn($q) => $q->where('status', 'selesai'))
            ->where('menus.canteen_id', $canteenId)
            ->groupBy('menus.name');

        if ($start && $end) {
            $query->whereBetween(DB::raw('DATE(order_items.created_at)'), [$start, $end]);
        }

        $data = $query->get();
        $pdf = Pdf::loadView('exports.menu_sales', compact('data'));

        return $pdf->download('laporan_penjualan_menu.pdf');
    }
}
