@extends('layouts.user')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 shadow-2xl">
                <div class="flex items-center space-x-4">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Riwayat Pesanan</h1>
                        <p class="text-blue-100 text-lg">Kelola dan pantau semua pesanan Anda dengan mudah</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border-0 overflow-hidden">
            <div class="bg-white p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-500 rounded-lg p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Daftar Pesanan</h3>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto" id="orders-table">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-800 to-gray-700">
                                <th class="px-4 py-4 text-center text-sm font-bold text-white uppercase tracking-wide rounded-tl-lg">
                                    <div class="flex items-center justify-center space-x-1">
                                        <span>No</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wide">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Tanggal</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wide">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                        </svg>
                                        <span>Menu</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wide">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Status</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wide">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span>Total</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wide">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <span>Pembayaran</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wide rounded-tr-lg">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <span>Status Bayar</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem !important;
    margin: 0.125rem !important;
    border-radius: 0.5rem !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
    color: white !important;
    border: none !important;
}
.dataTables_wrapper .dataTables_filter input {
    border-radius: 0.75rem !important;
    padding: 0.5rem 1rem !important;
    border: 2px solid #e5e7eb !important;
}
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
}
.dataTables_wrapper .dataTables_length select {
    border-radius: 0.5rem !important;
    padding: 0.25rem 0.5rem !important;
}
#orders-table tbody tr td {
    border: none !important;
    padding: 12px 8px !important;
    vertical-align: middle !important;
}
#orders-table tbody tr:hover td {
    background-color: #f8fafc !important;
}
.dataTables_wrapper .dataTables_scrollBody {
    border: none !important;
}
#orders-table.border-separate {
    border-spacing: 0 8px !important;
}
.bg-blue-25 {
    background-color: #f0f9ff !important;
}

@media (max-width: 768px) {
    .container {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length {
        margin-bottom: 1rem !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        width: 100% !important;
        max-width: none !important;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        text-align: center !important;
        margin-top: 1rem !important;
    }
    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control,
    table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control {
        position: relative;
        padding-left: 30px;
    }
    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
    table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
        top: 50%;
        left: 5px;
        height: 1em;
        width: 1em;
        margin-top: -0.5em;
        display: block;
        position: absolute;
        color: white;
        border: 2px solid white;
        border-radius: 1em;
        box-shadow: 0 0 3px #444;
        box-sizing: content-box;
        text-align: center;
        text-indent: 0 !important;
        font-family: 'Courier New', Courier, monospace;
        line-height: 1em;
        content: '+';
        background-color: #31b131;
    }
    table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td.dtr-control:before,
    table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th.dtr-control:before {
        content: '-';
        background-color: #d33333;
    }
    table.dataTable.dtr-inline.collapsed > tbody > tr.child {
        padding: 0 !important;
    }
    table.dataTable.dtr-inline.collapsed > tbody > tr.child > td {
        padding: 0 !important;
    }
    table.dataTable > tbody > tr.child ul.dtr-details {
        display: block;
        list-style-type: none;
        margin: 0;
        padding: 0;
        background: #f8fafc;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    table.dataTable > tbody > tr.child ul.dtr-details > li {
        border-bottom: 1px solid #e5e7eb;
        padding: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    table.dataTable > tbody > tr.child ul.dtr-details > li:last-child {
        border-bottom: none;
    }
    table.dataTable > tbody > tr.child ul.dtr-details > li .dtr-title {
        font-weight: 600;
        color: #374151;
        min-width: 100px;
        margin-right: 1rem;
    }
    table.dataTable > tbody > tr.child ul.dtr-details > li .dtr-data {
        text-align: right;
        flex: 1;
    }
    #orders-table thead th {
        font-size: 0.75rem !important;
        padding: 0.5rem 0.25rem !important;
    }
    #orders-table tbody td {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.25rem !important;
    }
    @media (max-width: 640px) {
        .mobile-hidden {
            display: none !important;
        }
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .container {
        padding-left: 2rem !important;
        padding-right: 2rem !important;
    }
}

@media (max-width: 768px) {
    .status-badge {
        font-size: 0.6rem !important;
        padding: 0.25rem 0.5rem !important;
    }
    .status-badge svg {
        width: 0.75rem !important;
        height: 0.75rem !important;
    }
    .number-circle {
        width: 1.5rem !important;
        height: 1.5rem !important;
        font-size: 0.75rem !important;
    }
    .price-display {
        font-size: 0.875rem !important;
    }
    .price-display svg {
        width: 0.875rem !important;
        height: 0.875rem !important;
    }
    .menu-text {
        font-size: 0.75rem !important;
        line-height: 1.2 !important;
        max-height: 2.4rem !important;
        overflow: hidden !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('user.orders.history.table') }}',
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            processing: "Memuat data..."
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'text-center font-semibold text-gray-600',
                render: function(data) {
                    return `<div class="bg-blue-50 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center mx-auto font-bold text-sm">${data}</div>`;
                }
            },
            {
                data: 'created_at',
                name: 'created_at',
                className: 'font-medium text-gray-700',
                render: function(data) {
                    return `<div class="flex items-center space-x-2">
                                <div class="bg-indigo-100 text-indigo-600 p-1 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span>${data || '-'}</span>
                            </div>`;
                }
            },
            {
                data: 'menus',
                name: 'menus',
                orderable: false,
                searchable: false,
                className: 'max-w-xs',
                render: function(data) {
                    return `<div class="text-gray-700 text-sm leading-relaxed">${data || '-'}</div>`;
                }
            },
            {
                data: 'status',
                name: 'status',
                className: 'text-center',
                render: function(data) {
                    const normalized = (data || '').toLowerCase().trim();
                    const statusConfig = {
                        'diproses': {
                            class: 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            icon: '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                        },
                        'selesai': {
                            class: 'bg-green-100 text-green-800 border-green-200',
                            icon: '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
                        },
                        'ditolak': {
                            class: 'bg-red-100 text-red-800 border-red-200',
                            icon: '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>'
                        }
                    };

                    const config = statusConfig[normalized] || {
                        class: 'bg-gray-100 text-gray-800 border-gray-200',
                        icon: '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    };

                    return `<div class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full border ${config.class}">
                                ${config.icon}
                                <span class="ml-1">${data || 'Unknown'}</span>
                            </div>`;
                }
            },
            {
                data: 'total_price',
                name: 'total_price',
                className: 'text-gray-700',
                render: function(data) {
                    return `<div class="flex items-center space-x-2">
                                <div class="bg-gray-100 text-gray-600 p-1 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <span>${data || '0'}</span>
                            </div>`;
                }
            },
            {
                data: 'payment_method',
                name: 'payment_method',
                className: 'font-medium text-gray-700',
                render: function(data) {
                    let icon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>';

                    if (data && data.toLowerCase().includes('cash')) {
                        icon = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>';
                    }

                    return `<div class="flex items-center space-x-2">
                                <div class="bg-purple-100 text-purple-600 p-1 rounded">
                                    ${icon}
                                </div>
                                <span>${data || '-'}</span>
                            </div>`;
                }
            },
          {
                        data: 'payment_status',
                        name: 'payment_status',
                        className: 'text-center',
                        render: function(data) {
                            const normalized = (data || '').toLowerCase().trim();
                            let statusClass = 'bg-gray-100 text-gray-800 border-gray-200';
                            let icon = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';

                            if (normalized.includes('lunas') || normalized.includes('paid') || normalized.includes('success')) {
                                statusClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                icon = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                            } else if (normalized.includes('pending') || normalized.includes('menunggu')) {
                                statusClass = 'bg-amber-100 text-amber-800 border-amber-200';
                                icon = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                            } else if (normalized.includes('gagal') || normalized.includes('failed')) {
                                statusClass = 'bg-red-100 text-red-800 border-red-200';
                                icon = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                            }

                            return `<div class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full border ${statusClass}">
                                        ${icon}
                                        <span class="ml-1">${data || 'Unknown'}</span>
                                    </div>`;
                        }
                    }
        ],
        drawCallback: function() {
            $('#orders-table tbody tr').hover(
                function() {
                    $(this).addClass('bg-blue-50 transform scale-[1.01] shadow-lg transition-all duration-200');
                },
                function() {
                    $(this).removeClass('bg-blue-50 transform scale-[1.01] shadow-lg transition-all duration-200');
                }
            );
        }
    });
});
</script>
@endpush
