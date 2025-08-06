<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Menu</th>
            <th>Jumlah Terjual</th>
            <th>Total Pendapatan</th>
            <th>Terakhir Terjual</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->menu_name }}</td>
                <td>{{ $item->total_terjual }}</td>
                <td>Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->terakhir_terjual)->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
