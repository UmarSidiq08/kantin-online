<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Menu</title>

</head>

<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN MENU</h1>
        <p>Kantin: {{ $data->first()->canteen->name ?? 'Semua Kantin' }}</p>
        @if (request('start') && request('end'))
            <p>Periode: {{ \Carbon\Carbon::parse(request('start'))->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse(request('end'))->format('d/m/Y') }}</p>
        @endif
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div>
            <h3>Total Item Terjual</h3>
            <p>{{ number_format($totalItems ?? 0) }}</p>
        </div>
        <div>
            <h3>Total Pendapatan</h3>
            <p class="currency">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
        </div>
        <div>
            <h3>Jumlah Menu</h3>
            <p>{{ $data->count() }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">Rank</th>
                <th style="width: 45%">Nama Menu</th>
                <th style="width: 15%">Qty Terjual</th>
                <th style="width: 20%">Total Pendapatan</th>
                <th style="width: 10%">Terakhir Terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                <tr>
                    <td class="text-center">
                        @if ($index < 3)
                            <span class="rank-badge">#{{ $index + 1 }}</span>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td><strong>{{ $item->menu_name }}</strong></td>
                    <td class="text-center">{{ number_format($item->total_terjual) }}</td>
                    <td class="text-right currency">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    <td class="text-center">
                        {{ $item->terakhir_terjual ? \Carbon\Carbon::parse($item->terakhir_terjual)->format('d/m/Y H:i') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 30px; color: #666;">
                        Tidak ada data penjualan untuk periode yang dipilih
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem</p>
    </div>
</body>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
    }

    .header h1 {
        margin: 0;
        color: #333;
        font-size: 24px;
    }

    .header p {
        margin: 5px 0;
        color: #666;
    }

    .summary {
        background-color: #f5f5f5;
        padding: 15px;
        margin: 20px 0;
        border-radius: 5px;
        display: flex;
        justify-content: space-around;
        text-align: center;
    }

    .summary div {
        flex: 1;
    }

    .summary h3 {
        margin: 0;
        color: #333;
    }

    .summary p {
        margin: 5px 0;
        font-size: 18px;
        font-weight: bold;
        color: #4472C4;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    th {
        background-color: #4472C4;
        color: white;
        font-weight: bold;
        padding: 12px 8px;
        text-align: center;
        border: 1px solid #ddd;
    }

    td {
        padding: 10px 8px;
        border: 1px solid #ddd;
        text-align: left;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f0f8ff;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .currency {
        color: #28a745;
        font-weight: bold;
    }

    .rank-badge {
        background-color: #FFD700;
        color: #333;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }

    .footer {
        margin-top: 30px;
        text-align: center;
        color: #666;
        font-size: 12px;
    }
</style>

</html>
