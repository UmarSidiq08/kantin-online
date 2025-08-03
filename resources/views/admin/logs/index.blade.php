@extends('layouts.admin')

@section('title', 'Riwayat Pesanan')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Aktivitas Pesanan</h1>
                    <p class="text-gray-600 mt-1">Kelola dan pantau semua aktivitas pesanan</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="text-sm text-gray-500">
                        Total: <span id="total-records" class="font-semibold text-gray-900">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Data</h3>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div class="space-y-2">
                    <label for="filter-status" class="block text-sm font-medium text-gray-700">
                        Status Pesanan
                    </label>
                    <select id="filter-status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">-- Semua Status --</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div class="space-y-2">
                    <label for="filter-start-date" class="block text-sm font-medium text-gray-700">
                        Dari Tanggal
                    </label>
                    <input type="date" id="filter-start-date"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- End Date -->
                <div class="space-y-2">
                    <label for="filter-end-date" class="block text-sm font-medium text-gray-700">
                        Sampai Tanggal
                    </label>
                    <input type="date" id="filter-end-date"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Aksi
                    </label>
                    <div class="flex space-x-2">
                        <button id="reset-filter" type="button"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors duration-200">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Data Riwayat Pesanan</h3>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto">
                    <table id="orderlog-table" class="w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kantin</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            let table = $('#orderlog-table').DataTable({
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
                    zeroRecords: "Tidak ditemukan data yang sesuai"
                },
                ajax: {
                    url: '{{ route('admin.logs.data') }}',
                    data: function(d) {
                        d.status = $('#filter-status').val();
                        d.start_date = $('#filter-start-date').val();
                        d.end_date = $('#filter-end-date').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'user',
                        name: 'user.name'
                    },
                    {
                        data: 'canteen',
                        name: 'canteen'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            const normalized = (data || '').toLowerCase().trim();
                            const statusClasses = {
                                'diproses': 'bg-yellow-100 text-yellow-800',
                                'selesai': 'bg-green-100 text-green-800',
                                'ditolak': 'bg-red-100 text-red-800'
                            };
                            const className = statusClasses[normalized] ||
                                'bg-gray-100 text-gray-800';
                            return `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${className}">${data || '-'}</span>`;
                        }

                    },
                    {
                        data: 'total_price',
                        name: 'total_price',
                        className: 'text-right'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
                drawCallback: function(settings) {
                    // Update total records
                    $('#total-records').text(settings.json.recordsTotal || 0);
                },
                order: [
                    [5, 'desc']
                ], // Sort by created_at desc
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ]
            });

            // Filter handlers
            $('#filter-status').change(function() {
                table.ajax.reload();
            });

            $('#apply-filter').click(function() {
                table.ajax.reload();
            });

            $('#reset-filter').click(function() {
                $('#filter-status').val('');
                $('#filter-start-date').val('');
                $('#filter-end-date').val('');
                table.ajax.reload();
            });

            // Auto-apply date filter when both dates are selected
            $('#filter-start-date, #filter-end-date').change(function() {
                if ($('#filter-start-date').val() && $('#filter-end-date').val()) {
                    table.ajax.reload();
                }
            });
        });
    </script>
@endpush
