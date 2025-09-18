@extends('layouts.admin')
@section('title', 'Riwayat Pesanan')
@section('content')
    <div class="mb-8">
        <div class="flex items-center mb-2">
            <div class="w-1 h-8 bg-purple-500 rounded-full mr-4"></div>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Pesanan</h1>
        </div>
        <p class="text-gray-600 text-lg ml-5">Kelola dan pantau semua aktivitas pesanan kantin.</p>
    </div>
    <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-4 sm:px-8 py-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Riwayat Aktivitas</h2>
                        <p class="text-sm text-gray-600 mt-1">Monitor semua transaksi pesanan</p>
                    </div>
                </div>
                <div class="bg-white px-6 py-3 rounded-lg border border-purple-200 shadow-sm">
                    <div class="text-sm text-gray-600">Total Pesanan:</div>
                    <div id="total-records" class="text-2xl font-bold text-purple-600">-</div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-4 sm:px-8 py-6 border-b border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Filter & Pencarian</h3>
                    <p class="text-sm text-gray-600 mt-1">Saring data berdasarkan kriteria tertentu</p>
                </div>
            </div>
        </div>
        <div class="p-4 sm:p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <label for="filter-status" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Status Pesanan
                    </label>
                    <select id="filter-status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white shadow-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label for="filter-start-date" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Dari Tanggal
                    </label>
                    <input type="date" id="filter-start-date"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white shadow-sm">
                </div>
                <div class="space-y-2">
                    <label for="filter-end-date" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Sampai Tanggal
                    </label>
                    <input type="date" id="filter-end-date"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 bg-white shadow-sm">
                </div>
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Aksi
                    </label>
                    <button id="reset-filter" type="button"
                        class="w-full px-4 py-3 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-4 sm:px-8 py-6 border-b border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Data Riwayat Pesanan</h3>
                    <p class="text-sm text-gray-600 mt-1">Daftar lengkap transaksi pesanan</p>
                </div>
            </div>
        </div>
        <div class="p-4 sm:p-8">
            <div class="block lg:hidden space-y-4" id="mobile-cards"></div>
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table id="orderlog-table" class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    User</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Kantin</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Total</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>
            </div>
            <div id="loading-state" class="hidden">
                <div class="flex items-center justify-center py-12">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
                        <span class="text-gray-600 font-medium">Memuat data pesanan...</span>
                    </div>
                </div>
            </div>
            <div id="empty-state" class="hidden">
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Riwayat Pesanan</h3>
                    <p class="text-gray-600 mb-6">Data pesanan akan muncul di sini setelah ada transaksi.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        let table, isMobile = window.innerWidth < 1024;
        const getStatusBadge = status => {
            const n = (status || '').toLowerCase().trim();
            const c = {
                'diproses': {
                    class: 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    icon: '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.414L10 9.586V6z" clip-rule="evenodd"></path></svg>',
                    label: 'Diproses'
                },
                'selesai': {
                    class: 'bg-green-100 text-green-800 border-green-200',
                    icon: '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
                    label: 'Selesai'
                },
                'ditolak': {
                    class: 'bg-red-100 text-red-800 border-red-200',
                    icon: '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
                    label: 'Ditolak'
                }
            };
            const cfg = c[n] || {
                class: 'bg-gray-100 text-gray-800 border-gray-200',
                icon: '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
                label: status || '-'
            };
            return `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full border ${cfg.class}">${cfg.icon}${cfg.label}</span>`;
        };
        const createMobileCard = data =>
            `<div class="bg-gradient-to-r from-white to-purple-50 rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200"><div class="flex justify-between items-start mb-4"><div class="flex-1"><h3 class="font-semibold text-gray-900 text-lg mb-1">${data.user}</h3><p class="text-sm text-gray-600">${data.canteen}</p></div>${getStatusBadge(data.status)}</div><div class="space-y-3"><div class="flex justify-between items-center"><span class="text-sm text-gray-600">Total Pembayaran:</span><span class="font-bold text-lg text-purple-600">${data.total_price}</span></div><div class="flex justify-between items-center"><span class="text-sm text-gray-600">Waktu Pesanan:</span><span class="text-sm text-gray-900 font-medium">${data.created_at}</span></div></div></div>`;
        const loadMobileData = () => {
            $('#loading-state').removeClass('hidden');
            $('#mobile-cards').empty();
            $('#empty-state').addClass('hidden');
            const params = {
                status: $('#filter-status').val(),
                start_date: $('#filter-start-date').val(),
                end_date: $('#filter-end-date').val()
            };
            $.get('{{ route('admin.logs.data') }}', params, function(response) {
                $('#loading-state').addClass('hidden');
                if (response.data && response.data.length > 0) {
                    const cards = response.data.map(item => createMobileCard(item)).join('');
                    $('#mobile-cards').html(cards);
                    $('#total-records').text(response.recordsTotal || response.data.length);
                } else {
                    $('#empty-state').removeClass('hidden');
                    $('#total-records').text('0');
                }
            }).fail(function() {
                $('#loading-state').addClass('hidden');
                toastr.error('Gagal memuat data pesanan');
                $('#total-records').text('-');
            });
        };
        const initializeTable = () => {
            if (!isMobile) {
                table = $('#orderlog-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: false,
                    language: {
                        processing: '<div class="flex items-center justify-center py-8"><div class="flex items-center space-x-3"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-500"></div><span class="text-gray-600">Memuat data...</span></div></div>',
                        search: '',
                        searchPlaceholder: 'Cari pesanan...',
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
                            className: 'px-6 py-3 whitespace-nowrap text-sm text-gray-900 text-center'
                        },
                        {
                            data: 'user',
                            name: 'user.name',
                            className: 'px-6 py-3 text-sm font-semibold text-gray-900'
                        },
                        {
                            data: 'canteen',
                            name: 'canteen',
                            className: 'px-6 py-2 text-sm text-gray-600'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            className: 'px-6 py-3 whitespace-nowrap text-sm',
                            render: function(data) {
                                return getStatusBadge(data);
                            }
                        },
                        {
                            data: 'total_price',
                            name: 'total_price',
                            className: 'px-6 py-3 whitespace-nowrap text-sm font-bold text-purple-600 text-right'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            className: 'px-6 py-3 whitespace-nowrap text-sm text-gray-600'
                        }
                    ],
                    drawCallback: function(settings) {
                        $('#total-records').text(settings.json.recordsTotal || 0);
                    },
                    order: [
                        [5, 'desc']
                    ],
                    pageLength: 25,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],
                    dom: '<"flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4"<"mb-4 lg:mb-0"l><"flex items-center space-x-4"f>>rtip'
                });
            } else {
                loadMobileData();
            }
        };
        const reloadData = () => {
            isMobile ? loadMobileData() : table && table.ajax.reload();
        };
        $(document).ready(function() {
            const checkScreenSize = () => {
                const newIsMobile = window.innerWidth < 1024;
                if (newIsMobile !== isMobile) {
                    isMobile = newIsMobile;
                    if (table) {
                        table.destroy();
                        table = null;
                    }
                    if (isMobile) {
                        $('.hidden.lg\\:block').addClass('hidden');
                        $('.block.lg\\:hidden').removeClass('hidden');
                    } else {
                        $('.hidden.lg\\:block').removeClass('hidden');
                        $('.block.lg\\:hidden').addClass('hidden');
                    }
                    initializeTable();
                }
            };
            $(window).resize(checkScreenSize);
            initializeTable();
            $('#filter-status').change(function() {
                reloadData();
            });
            $('#reset-filter').click(function() {
                $('#filter-status').val('');
                $('#filter-start-date').val('');
                $('#filter-end-date').val('');
                reloadData();
            });
            $('#filter-start-date, #filter-end-date').change(function() {
                if ($('#filter-start-date').val() && $('#filter-end-date').val()) reloadData();
            });
        });
    </script>
    <style>
        .dataTables_wrapper {
            font-family: inherit;
        }

        .dataTables_filter input {
            @apply border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200;
        }

        .dataTables_length select {
            @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200;
        }

        .dataTables_info {
            @apply text-gray-600 text-sm;
        }

        .dataTables_paginate .paginate_button {
            @apply px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200;
        }

        .dataTables_paginate .paginate_button.current {
            @apply bg-purple-500 text-white border-purple-500;
        }

        .dataTables_paginate .paginate_button.disabled {
            @apply text-gray-400 cursor-not-allowed hover:bg-white;
        }

        @media (max-width: 640px) {
            .grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-4 {
                grid-template-columns: 1fr;
            }
        }

        #mobile-cards::-webkit-scrollbar {
            width: 6px;
        }

        #mobile-cards::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        #mobile-cards::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        #mobile-cards::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endpush
