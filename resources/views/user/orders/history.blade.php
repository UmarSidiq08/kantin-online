@extends('layouts.user')

@section('title', 'Riwayat Pesanan')

@section('content')

    <div class="container mt-4">
        <h2>Riwayat Pesanan Saya</h2>

        @if ($orders->isEmpty())
            <p>Tidak ada pesanan.</p>
        @else
            
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <span
                                    class="badge
                                @if ($order->status === $statusLabels['PENDING']) bg-warning
                                @elseif ($order->status === $statusLabels['DIPROSES']) bg-info
                                @elseif ($order->status === $statusLabels['SELESAI']) bg-success
                                @elseif ($order->status === $statusLabels['DITOLAK']) bg-danger @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
