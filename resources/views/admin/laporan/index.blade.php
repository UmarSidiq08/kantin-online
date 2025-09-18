@extends('layouts.admin')
@section('title', 'Laporan Penjualan')
@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-[98vw] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Laporan Penjualan</h1>
                    <p class="mt-2 text-sm text-gray-600">Kelola dan analisis data penjualan Anda</p>
                </div>
                <div class="flex items-center justify-center h-12 w-12 bg-blue-100 rounded-full">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">📅 Ringkasan Penjualan</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($summaryData as $data)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-{{ $data['color'] }}-500 to-{{ $data['color'] }}-600 p-6">
                                <h4 class="text-white text-sm font-medium mb-2">{{ $data['title'] }}</h4>
                                <p class="text-3xl font-bold text-white">Rp {{ number_format($data['value'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Grafik Pendapatan 7 Hari Terakhir</h3>
                    <p class="mt-1 text-sm text-gray-500">Tren penjualan dalam seminggu terakhir</p>
                </div>
                <div class="p-6">
                    <div class="h-[400px] w-full">
                        <canvas id="chartCanvas" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Filter & Pengaturan</h3>
                    <p class="mt-1 text-sm text-gray-500">Sesuaikan tampilan laporan sesuai kebutuhan</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($filters as $filter)
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">{{ $filter['label'] }}</label>
                                <div class="relative">
                                    <input type="{{ $filter['type'] }}" id="{{ $filter['id'] }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Urutkan Berdasarkan</label>
                            <select id="sort_by"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">-- Pilih Urutan --</option>
                                <option value="total_terjual_desc">Jumlah Terjual (Terbesar)</option>
                                <option value="total_terjual_asc">Jumlah Terjual (Terkecil)</option>
                                <option value="total_pendapatan_desc">Pendapatan (Terbesar)</option>
                                <option value="total_pendapatan_asc">Pendapatan (Terkecil)</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button id="reset-filter"
                                class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200 flex items-center justify-center group">
                                <svg class="h-5 w-5 mr-2 group-hover:rotate-180 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Data Penjualan</h3>
                            <p class="mt-1 text-sm text-gray-500">Ringkasan penjualan menu berdasarkan periode yang dipilih
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            @foreach ($exportButtons as $btn)
                                <a href="{{ route($btn['route'], ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                                    class="inline-flex items-center px-4 py-2 bg-{{ $btn['color'] }}-600 hover:bg-{{ $btn['color'] }}-700 text-white text-sm font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $btn['icon'] }}"></path>
                                    </svg>
                                    {{ $btn['text'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="overflow-hidden">
                    <div class="overflow-x-auto px-6 py-4">
                        <div class="min-w-[800px]">
                            <table class="w-full divide-y divide-gray-200" id="laporan-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        @foreach ($headers as $header)
                                            <th
                                                class="px-6 py-4 {{ $header['class'] }} text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                {{ $header['text'] }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            const showLoading = () => $('#loading-overlay').removeClass('hidden');
            const hideLoading = () => $('#loading-overlay').addClass('hidden');
            const tableConfig = {
                processing: true,
                serverSide: true,
                responsive: true,
                language: {
                    processing: "Memuat data...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                    emptyTable: "Tidak ada data yang tersedia",
                    zeroRecords: "Tidak ada data yang cocok"
                },
                ajax: {
                    url: "{{ route('admin.laporan.data') }}",
                    data: d => ({
                        ...d,
                        start_date: $('#filter-start-date').val(),
                        end_date: $('#filter-end-date').val(),
                        sort_by: $('#sort_by').val()
                    }),
                    beforeSend: showLoading,
                    complete: hideLoading
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center text-gray-600',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'menu',
                        name: 'menu_name',
                        className: 'text-gray-900'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        className: 'text-center text-gray-900'
                    },
                    {
                        data: 'total',
                        name: 'total',
                        className: 'text-right text-gray-900'
                    },
                    {
                        data: 'terakhir',
                        name: 'terakhir',
                        className: 'text-center text-gray-600'
                    }
                ],
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                order: [
                    [3, 'desc']
                ]
            };
            const table = $('#laporan-table').DataTable(tableConfig);
            const reloadTable = () => {
                showLoading();
                table.ajax.reload(hideLoading);
            };
            $('#sort_by').on('change', reloadTable);
            $('#filter-start-date, #filter-end-date').change(function() {
                const startDate = $('#filter-start-date').val(),
                    endDate = $('#filter-end-date').val();
                if (startDate && endDate && startDate > endDate) {
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                    return;
                }
                if ((!startDate && !endDate) || (startDate && endDate)) reloadTable();
            });
            $('#reset-filter').click(function() {
                $('#filter-start-date, #filter-end-date, #sort_by').val('');
                reloadTable();
            });
            $('.inline-flex[href*="export"]').click(function() {
                const button = $(this),
                    originalHtml = button.html();
                button.html(
                    '<svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengunduh...'
                    );
                setTimeout(() => button.html(originalHtml), 3000);
            });
            $.get("{{ route('admin.laporan.chart') }}", function(data) {
                const ctx = document.getElementById('chartCanvas').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.date),
                        datasets: [{
                            label: 'Pendapatan 7 Hari Terakhir',
                            data: data.map(item => item.total),
                            borderWidth: 3,
                            fill: true,
                            backgroundColor: 'rgba(76, 175, 80, 0.1)',
                            borderColor: '#4caf50',
                            tension: 0.3,
                            pointBackgroundColor: '#4caf50',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: value => 'Rp ' + value.toLocaleString()
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
