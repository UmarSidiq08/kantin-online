@extends('layouts.admin')
@section('title', 'Kelola Diskon Menu')
@section('page-title', 'Kelola Diskon Menu')
@section('modal')
    @include('admin.discounts.modal.add')
    @include('admin.discounts.modal.edit')
@endsection
@section('content')
    <div class="mb-8">
        <div class="flex items-center mb-2">
            <div class="w-1 h-8 bg-orange-500 rounded-full mr-4"></div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Diskon Menu</h1>
        </div>
        <p class="text-gray-600 text-lg ml-5">Kelola promosi dan diskon untuk menu kantin Anda.</p>
    </div>
    <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-50 to-red-50 px-4 sm:px-8 py-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Manajemen Diskon</h2>
                        <p class="text-sm text-gray-600 mt-1">Atur promosi dan penawaran khusus</p>
                    </div>
                </div>
                <button
                    class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold flex items-center justify-center w-full sm:w-auto"
                    data-bs-toggle="modal" data-bs-target="#modal_add">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Tambah Diskon</span>
                    <span class="sm:hidden">Tambah</span>
                </button>
            </div>
        </div>
        <div class="p-4 sm:p-8">
            <div class="block lg:hidden space-y-4" id="mobile-cards"></div>
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="table table-striped w-full" id="table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    No</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Menu</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Harga Asli</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Diskon</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Harga Setelah Diskon</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Periode</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Penghematan</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="loading-state" class="hidden">
                <div class="flex items-center justify-center py-12">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500"></div>
                        <span class="text-gray-600 font-medium">Memuat data...</span>
                    </div>
                </div>
            </div>
            <div id="empty-state" class="hidden">
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Diskon</h3>
                    <p class="text-gray-600 mb-6">Mulai buat diskon pertama Anda untuk menarik lebih banyak pelanggan.</p>
                    <button
                        class="bg-gradient-to-r from-orange-500 to-orange-600 text-white px-6 py-3 rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold flex items-center mx-auto"
                        data-bs-toggle="modal" data-bs-target="#modal_add">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Diskon Pertama
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let table, isMobile = window.innerWidth < 1024;
        const onEdit = (id, menuId, type, value, startDate, endDate, startTime, endTime, description, isActive) => {
            $('#modal_edit').modal('show');
            $('#modal_edit input[name="id"]').val(id);
            $('#modal_edit select[name="menu_id"]').val(menuId);
            $('#modal_edit select[name="type"]').val(type);
            $('#modal_edit input[name="value"]').val(value);
            $('#modal_edit input[name="start_date"]').val(startDate);
            $('#modal_edit input[name="end_date"]').val(endDate);
            $('#modal_edit input[name="start_time"]').val(startTime);
            $('#modal_edit input[name="end_time"]').val(endTime);
            $('#modal_edit textarea[name="description"]').val(description);
            $('#modal_edit input[name="is_active"]').prop('checked', isActive == 1);
            updateValuePlaceholder('#modal_edit');
        };
        const onDelete = id => {
            if (confirm("Yakin ingin menghapus diskon ini?")) {
                $.post("{{ route('admin.discount.destroy') }}", {
                    id
                }, function(res) {
                    toastr.success(res.message, 'Berhasil');
                    loadData();
                }).fail(function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan');
                });
            }
        };
        const onToggleStatus = id => {
            $.post("{{ route('admin.discount.toggle-status') }}", {
                id
            }, function(res) {
                toastr.success(res.message, 'Berhasil');
                loadData();
            }).fail(function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan');
            });
        };
        const updateValuePlaceholder = modalSelector => {
            const typeSelect = $(modalSelector + ' select[name="type"]'),
                valueInput = $(modalSelector + ' input[name="value"]');
            if (typeSelect.val() === 'percentage') {
                valueInput.attr('placeholder', 'Contoh: 20 untuk 20%').attr('max', '100');
                valueInput.next('.form-text').text('Masukkan nilai persentase (1-100)');
            } else {
                valueInput.attr('placeholder', 'Contoh: 5000 untuk Rp 5.000').removeAttr('max');
                valueInput.next('.form-text').text('Masukkan nilai nominal dalam rupiah');
            }
        };
        const updateMenuPrice = modalSelector => {
            const menuId = $(modalSelector + ' select[name="menu_id"]').val(),
                priceDisplay = $(modalSelector + ' .menu-price-display');
            if (menuId) {
                $.get("{{ route('admin.discount.menu-price', ':id') }}".replace(':id', menuId), function(res) {
                    priceDisplay.html('<strong>Harga Menu: ' + res.formatted_price + '</strong>').show();
                    if ($(modalSelector + ' select[name="type"]').val() === 'fixed') {
                        $(modalSelector + ' input[name="value"]').attr('max', res.price - 1);
                    }
                });
            } else {
                priceDisplay.hide();
            }
        };
        const createMobileCard = data => {
            const statusBadge = data.status.includes('Aktif');
            return `<div class="bg-gradient-to-r from-white to-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200"><div class="flex justify-between items-start mb-4"><div class="flex-1"><h3 class="font-semibold text-gray-900 text-lg">${data.menu_name}</h3><p class="text-sm text-gray-600 mt-1">Menu Item</p></div>${statusBadge}</div><div class="space-y-3"><div class="flex justify-between items-center"><span class="text-sm text-gray-600">Harga Asli:</span><span class="font-medium text-gray-900">${data.original_price}</span></div><div class="flex justify-between items-center"><span class="text-sm text-gray-600">Diskon:</span><span class="font-medium text-orange-600">${data.discount_value}</span></div><div class="flex justify-between items-center"><span class="text-sm text-gray-600">Harga Setelah Diskon:</span><span class="font-bold text-green-600">${data.discounted_price}</span></div><div class="flex justify-between items-center"><span class="text-sm text-gray-600">Penghematan:</span><span class="font-medium text-red-500">${data.savings}</span></div><div class="pt-2 border-t border-gray-200"><p class="text-sm text-gray-600 mb-2">Periode:</p><p class="text-sm text-gray-900">${data.period}</p></div></div><div class="flex gap-2 mt-6 pt-4 border-t border-gray-200">${data.action}</div></div>`;
        };
        const loadData = () => {
            if (isMobile) {
                $('#loading-state').removeClass('hidden');
                $('#mobile-cards').empty();
                $('#empty-state').addClass('hidden');
                $.get("{{ route('admin.discount.table') }}", function(response) {
                    $('#loading-state').addClass('hidden');
                    if (response.data && response.data.length > 0) {
                        const cards = response.data.map(item => createMobileCard(item)).join('');
                        $('#mobile-cards').html(cards);
                    } else {
                        $('#empty-state').removeClass('hidden');
                    }
                }).fail(function() {
                    $('#loading-state').addClass('hidden');
                    toastr.error('Gagal memuat data');
                });
            } else {
                if (table) table.ajax.reload();
            }
        };
        const initializeTable = () => {
            if (!isMobile) {
                table = $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.discount.table') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'px-6 py-4 whitespace-nowrap text-sm text-gray-900'
                        },
                        {
                            data: 'menu_name',
                            className: 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'
                        },
                        {
                            data: 'original_price',
                            className: 'px-6 py-4 whitespace-nowrap text-sm text-gray-900'
                        },
                        {
                            data: 'discount_value',
                            className: 'px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-medium'
                        },
                        {
                            data: 'discounted_price',
                            className: 'px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold'
                        },
                        {
                            data: 'period',
                            orderable: false,
                            className: 'px-6 py-4 text-sm text-gray-900'
                        },
                        {
                            data: 'status',
                            orderable: false,
                            className: 'px-6 py-4 whitespace-nowrap text-sm'
                        },
                        {
                            data: 'savings',
                            orderable: false,
                            className: 'px-6 py-4 whitespace-nowrap text-sm text-red-500 font-medium'
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'px-6 py-4 whitespace-nowrap text-sm'
                        }
                    ],
                    order: [
                        [0, 'desc']
                    ],
                    responsive: false,
                    dom: '<"flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4"<"mb-4 lg:mb-0"l><"flex items-center space-x-4"f>>rtip',
                    language: {
                        search: '',
                        searchPlaceholder: 'Cari diskon...',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                        infoEmpty: 'Tidak ada data',
                        infoFiltered: '(difilter dari _MAX_ total data)',
                        paginate: {
                            first: 'Pertama',
                            last: 'Terakhir',
                            next: 'Selanjutnya',
                            previous: 'Sebelumnya'
                        },
                        processing: '<div class="flex items-center justify-center py-8"><div class="flex items-center space-x-3"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-orange-500"></div><span class="text-gray-600">Memuat data...</span></div></div>'
                    }
                });
            } else {
                loadData();
            }
        };
        $(function() {
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
            $('#modal_add select[name="type"], #modal_edit select[name="type"]').change(function() {
                updateValuePlaceholder('#' + $(this).closest('.modal').attr('id'));
            });
            $('#modal_add select[name="menu_id"], #modal_edit select[name="menu_id"]').change(function() {
                updateMenuPrice('#' + $(this).closest('.modal').attr('id'));
            });
            $('#form_add').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this),
                    btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');
                $.ajax({
                    url: "{{ route('admin.discount.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        $('#modal_add').modal('hide');
                        loadData();
                        toastr.success(res.message, 'Berhasil');
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-save me-2"></i>Simpan');
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let errorMsg = '';
                            Object.keys(errors).forEach(key => {
                                errorMsg += errors[key][0] + '\n';
                            });
                            toastr.error(errorMsg);
                        } else {
                            toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan');
                        }
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-save me-2"></i>Simpan');
                    }
                });
            });
            $('#form_edit').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this),
                    btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...');
                $.ajax({
                    url: "{{ route('admin.discount.update') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        $('#modal_edit').modal('hide');
                        loadData();
                        toastr.success(res.message, 'Berhasil');
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-save me-2"></i>Perbarui');
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let errorMsg = '';
                            Object.keys(errors).forEach(key => {
                                errorMsg += errors[key][0] + '\n';
                            });
                            toastr.error(errorMsg);
                        } else {
                            toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan');
                        }
                        btn.prop('disabled', false).html(
                            '<i class="fas fa-save me-2"></i>Perbarui');
                    }
                });
            });
            $('#modal_add').on('hidden.bs.modal', function() {
                $('#form_add')[0].reset();
                $('.menu-price-display').hide();
            });
            $('#modal_edit').on('hidden.bs.modal', function() {
                $('.menu-price-display').hide();
            });
            updateValuePlaceholder('#modal_add');
            updateValuePlaceholder('#modal_edit');
        });
    </script>
    <style>
        .dataTables_wrapper {
            font-family: inherit;
        }

        .dataTables_filter input {
            @apply border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200;
        }

        .dataTables_length select {
            @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200;
        }

        .dataTables_info {
            @apply text-gray-600 text-sm;
        }

        .dataTables_paginate .paginate_button {
            @apply px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200;
        }

        .dataTables_paginate .paginate_button.current {
            @apply bg-orange-500 text-white border-orange-500;
        }

        .dataTables_paginate .paginate_button.disabled {
            @apply text-gray-400 cursor-not-allowed hover:bg-white;
        }

        @media (max-width: 640px) {
            .btn-sm {
                @apply px-2 py-1 text-xs;
            }

            .btn-sm svg {
                @apply w-3 h-3;
            }
        }

        .space-y-4::-webkit-scrollbar {
            width: 6px;
        }

        .space-y-4::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .space-y-4::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .space-y-4::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endsection
