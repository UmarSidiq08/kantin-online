<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\OrderLog;

class LogOrderController extends Controller
{
  public function data(Request $request)
{
    $logs = OrderLog::with(['user', 'canteen'])->latest();

    if ($request->filled('status')) {
        $logs->where('status', $request->status);
    }
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $start = date('Y-m-d 00:00:00', strtotime($request->start_date));
        $end = date('Y-m-d 23:59:59', strtotime($request->end_date));
        $logs->whereBetween('created_at', [$start, $end]);
    }
      if ($request->has('search') && $request->search['value']) {
            $searchTerm = $request->search['value'];
            $logs = $logs->whereHas('user', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

    return DataTables::of($logs)
        ->addIndexColumn()
        ->addColumn('user', fn($log) => $log->user->name ?? '-')
        ->addColumn('canteen', fn($log) => $log->canteen->name ?? '-')
        ->editColumn('created_at', fn($log) => $log->created_at->format('d M Y H:i'))
        ->addColumn('status', fn($log) => ucfirst($log->status ?? '-'))
        ->addColumn('total_price', fn($log) => 'Rp ' . number_format($log->total_price ?? 0, 0, ',', '.'))

        ->filterColumn('user', function ($query, $keyword) {
            $query->whereHas('user', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            });
        })

        ->make(true);
}

}
