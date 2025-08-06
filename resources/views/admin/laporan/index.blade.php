@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-full mx-auto px-6 sm:px-8 lg:px-12">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Laporan Penjualan</h1>
                        <p class="mt-1 text-sm text-gray-600">Kelola dan analisis data penjualan Anda</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Filter & Pengaturan</h3>
                    <p class="mt-1 text-sm text-gray-500">Sesuaikan tampilan laporan sesuai kebutuhan</p>
                </div>

                <div class="p-6">
                    <!-- Date Filter Row -->
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                            <div class="relative">
                                <input type="date" id="filter-start-date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                            <div class="relative">
                                <input type="date" id="filter-end-date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Urutkan Berdasarkan</label>
                            <select id="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">-- Pilih Urutan --</option>
                                <option value="total_terjual_desc">Jumlah Terjual (Terbesar)</option>
                                <option value="total_terjual_asc">Jumlah Terjual (Terkecil)</option>
                                <option value="total_pendapatan_desc">Pendapatan (Terbesar)</option>
                                <option value="total_pendapatan_asc">Pendapatan (Terkecil)</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button id="reset-filter" class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </button>
                        </div>
                    </div>


                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Data Penjualan</h3>
                            <p class="mt-1 text-sm text-gray-500">Ringkasan penjualan menu berdasarkan periode yang dipilih</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.laporan.export.excel', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Excel
                            </a>

                            <a href="{{ route('admin.laporan.export.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                PDF
                            </a>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden">
                    <div class="overflow-x-auto px-6 py-4">
                        <table class="min-w-full divide-y divide-gray-200" id="laporan-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                        No
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Menu
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                        Jumlah Terjual
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-40">
                                        Total Pendapatan
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-40">
                                        Terakhir Terjual
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- DataTable will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Show loading overlay
            function showLoading() {
                $('#loading-overlay').removeClass('hidden');
            }

            // Hide loading overlay
            function hideLoading() {
                $('#loading-overlay').addClass('hidden');
            }

            let table = $('#laporan-table').DataTable({
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
                    data: function(d) {
                        d.start_date = $('#filter-start-date').val();
                        d.end_date = $('#filter-end-date').val();
                        d.sort_by = $('#sort_by').val();
                    },
                    beforeSend: function() {
                        showLoading();
                    },
                    complete: function() {
                        hideLoading();
                    }
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
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                order: [[3, 'desc']] // Default sort by total pendapatan desc
            });

            // Sort change handler
            $('#sort_by').on('change', function() {
                showLoading();
                table.ajax.reload(function() {
                    hideLoading();
                });
            });

            // Date filter change handler
            $('#filter-start-date, #filter-end-date').change(function() {
                const startDate = $('#filter-start-date').val();
                const endDate = $('#filter-end-date').val();

                if (startDate && endDate) {
                    if (startDate > endDate) {
                        alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                        return;
                    }
                    showLoading();
                    table.ajax.reload(function() {
                        hideLoading();
                    });
                } else if (!startDate && !endDate) {
                    showLoading();
                    table.ajax.reload(function() {
                        hideLoading();
                    });
                }
            });

            // Reset filter handler
            $('#reset-filter').click(function() {
                $('#filter-start-date').val('');
                $('#filter-end-date').val('');
                $('#sort_by').val('');
                showLoading();
                table.ajax.reload(function() {
                    hideLoading();
                });
            });

            // Add loading state to export buttons
            $('.inline-flex[href*="export"]').click(function() {
                const button = $(this);
                const originalHtml = button.html();
                button.html('<svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengunduh...');

                setTimeout(function() {
                    button.html(originalHtml);
                }, 3000);
            });
        });
    </script>
@endpush
