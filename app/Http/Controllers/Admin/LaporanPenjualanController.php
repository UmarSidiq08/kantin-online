<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\OrderItem;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\MenuSalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        $canteen = auth()->user()->canteen;
        if (!$canteen || !$canteen->is_premium) {
            return redirect()->route('admin.premium.show')->with('error', 'Fitur ini hanya tersedia untuk member premium.');
        }
        $canteenId = $canteen->id;
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        $totalToday = OrderItem::whereHas('order', function ($q) use ($today) {
            $q->whereDate('created_at', $today)->where('status', 'selesai');
        })->whereHas('menu', function ($q) use ($canteenId) {
            $q->where('canteen_id', $canteenId);
        })->sum(DB::raw('quantity * price'));
        $totalYesterday = OrderItem::whereHas('order', function ($q) use ($yesterday) {
            $q->whereDate('created_at', $yesterday)->where('status', 'selesai');
        })->whereHas('menu', function ($q) use ($canteenId) {
            $q->where('canteen_id', $canteenId);
        })->sum(DB::raw('quantity * price'));
        $totalThisMonth = OrderItem::whereHas('order', function ($q) use ($month, $year) {
            $q->whereMonth('created_at', $month)->whereYear('created_at', $year)->where('status', 'selesai');
        })->whereHas('menu', function ($q) use ($canteenId) {
            $q->where('canteen_id', $canteenId);
        })->sum(DB::raw('quantity * price'));
        $summaryData = [
            ['title' => 'Penjualan Hari Ini', 'value' => $totalToday, 'color' => 'green'],
            ['title' => 'Penjualan Kemarin', 'value' => $totalYesterday, 'color' => 'blue'],
            ['title' => 'Bulan ' . \Carbon\Carbon::create()->month($month)->locale('id')->isoFormat('MMMM') . ' ' . $year, 'value' => $totalThisMonth, 'color' => 'purple'],
        ];
        $filters = [
            ['id' => 'filter-start-date', 'label' => 'Dari Tanggal', 'type' => 'date'],
            ['id' => 'filter-end-date', 'label' => 'Sampai Tanggal', 'type' => 'date'],
        ];
        $exportButtons = [
            ['route' => 'admin.laporan.export.excel', 'color' => 'emerald', 'icon' => 'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'text' => 'Export Excel'],
            ['route' => 'admin.laporan.export.pdf', 'color' => 'red', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'text' => 'Export PDF'],
        ];
        $headers = [
            ['text' => 'No', 'class' => 'w-16 text-left'],
            ['text' => 'Menu', 'class' => 'text-left'],
            ['text' => 'Jumlah Terjual', 'class' => 'w-32 text-center'],
            ['text' => 'Total Pendapatan', 'class' => 'w-40 text-right'],
            ['text' => 'Terakhir Terjual', 'class' => 'w-40 text-center'],
        ];
        return view('admin.laporan.index', compact('today', 'totalToday', 'totalYesterday', 'totalThisMonth', 'month', 'year', 'summaryData', 'filters', 'exportButtons', 'headers'));
    }

    public function data(Request $request)
    {
        $canteenId = Auth::user()->canteen->id;
        $start = $request->start_date;
        $end = $request->end_date;
        $sortBy = $request->sort_by;
        $query = OrderItem::with('menu')->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select(
                'order_items.menu_id',
                'menus.name as menu_name',
                DB::raw('SUM(order_items.quantity) as total_terjual'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_pendapatan'),
                DB::raw('MAX(order_items.created_at) as terakhir_terjual')
            )->whereHas('order', fn($q) => $q->where('status', 'selesai'))->where('menus.canteen_id', $canteenId)->groupBy('order_items.menu_id', 'menus.name');

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
                $query->orderBy('terakhir_terjual', 'desc');
                break;
        }
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('menu', fn($item) => $item->menu_name ?? '-')
            ->addColumn('jumlah', fn($item) => $item->total_terjual)
            ->addColumn('total', fn($item) => 'Rp ' . number_format($item->total_pendapatan, 0, ',', '.'))
            ->addColumn('terakhir', fn($item) => \Carbon\Carbon::parse($item->terakhir_terjual)->format('Y-m-d H:i'))
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

    $data = $query->orderBy('total_terjual', 'desc')->get();

    // Hitung total untuk summary
    $totalItems = $data->sum('total_terjual');
    $totalRevenue = $data->sum('total_pendapatan');

    $pdf = Pdf::loadView('exports.menu_sales', compact('data', 'totalItems', 'totalRevenue'));
    return $pdf->download('laporan_penjualan_menu.pdf');
}

    public function chartData()
    {
        $canteenId = auth()->user()->canteen->id;
        $dailyData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $total = OrderItem::whereHas('order', function ($q) use ($date) {
                $q->whereDate('created_at', $date)->where('status', 'selesai');
            })->whereHas('menu', function ($q) use ($canteenId) {
                $q->where('canteen_id', $canteenId);
            })->sum(DB::raw('quantity * price'));
            $dailyData->push(['date' => Carbon::parse($date)->format('d M'), 'total' => $total]);
        }
        return response()->json($dailyData);
    }
}
