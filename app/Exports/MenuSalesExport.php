<?php

namespace App\Exports;

use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class MenuSalesExport implements FromView
{
    protected $start, $end, $canteenId;

    public function __construct($start, $end, $canteenId)
    {
        $this->start = $start;
        $this->end = $end;
        $this->canteenId = $canteenId;
    }

    public function view(): View
    {
        $query = OrderItem::join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                'menus.name as menu_name',
                DB::raw('SUM(order_items.quantity) as total_terjual'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_pendapatan'),
                DB::raw('MAX(order_items.created_at) as terakhir_terjual')
            )
            ->where('orders.status', 'selesai')
            ->where('menus.canteen_id', $this->canteenId)
            ->groupBy('menus.id', 'menus.name');

        if ($this->start && $this->end) {
            $query->whereBetween(DB::raw('DATE(order_items.created_at)'), [$this->start, $this->end]);
        }

        $data = $query->orderBy('total_terjual', 'desc')->get();

        // Hitung summary dengan benar
        $totalItems = $data->sum('total_terjual');
        $totalRevenue = $data->sum('total_pendapatan');

        return view('exports.menu_sales', compact('data', 'totalItems', 'totalRevenue'));
    }


    }

