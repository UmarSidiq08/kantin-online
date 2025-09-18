@extends('layouts.admin')

@section('title', 'Master Menu')
@section('page-title', 'Data Menu')

@section('modal')
    @include('admin.menus.modal.add')
    @include('admin.menus.modal.edit')
@endsection

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center mb-2">
            <div class="w-1 h-8 bg-green-500 rounded-full mr-4"></div>
            <h1 class="text-3xl font-bold text-gray-900">Master Menu</h1>
        </div>
        <p class="text-gray-600 text-lg ml-5">Kelola daftar menu kantin dan informasi produk Anda.</p>
    </div>

    <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 sm:px-8 py-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Daftar Menu</h2>
                        <p class="text-sm text-gray-600 mt-1">Kelola produk dan informasi menu kantin</p>
                    </div>
                </div>
                <button class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold flex items-center justify-center w-full sm:w-auto"
                        data-bs-toggle="modal" data-bs-target="#modal_add">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Tambah Menu</span>
                    <span class="sm:hidden">Tambah</span>
                </button>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-4 sm:p-8">
            <!-- Mobile Grid View (Hidden on Desktop) -->
            <div class="block lg:hidden" id="mobile-grid">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="mobile-cards">
                    <!-- Cards will be populated by JavaScript -->
                </div>
            </div>

            <!-- Desktop Table View (Hidden on Mobile) -->
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="table w-full" id="table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Gambar</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loading-state" class="hidden">
                <div class="flex items-center justify-center py-12">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500"></div>
                        <span class="text-gray-600 font-medium">Memuat data menu...</span>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="hidden">
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Menu</h3>
                    <p class="text-gray-600 mb-6">Mulai dengan menambahkan menu pertama untuk kantin Anda.</p>
                    <button class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold flex items-center mx-auto"
                            data-bs-toggle="modal" data-bs-target="#modal_add">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Menu Pertama
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

    let table;
    let isMobile = window.innerWidth < 1024;

    const onEdit = (id, name, description, category, price) => {
        $('#modal_edit').modal('show');
        $('#modal_edit input[name="id"]').val(id);
        $('#modal_edit input[name="name"]').val(name);
        $('#modal_edit textarea[name="description"]').val(description);
        $('#modal_edit select[name="category"]').val(category);
        $('#modal_edit input[name="price"]').val(price);
    };

    const onDelete = (id) => {
        if (confirm("Yakin ingin menghapus menu ini?")) {
            $.post("{{ route('admin.menu.destroy') }}", {
                id
            }, function(res) {
                toastr.success(res.message, 'Berhasil 🚀');
                loadData();
            }).fail(function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan');
            });
        }
    };

    const createMobileCard = (data) => {
        const truncateText = (text, maxLength) => {
            if (!text) return '-';
            return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
        };

        const imageHtml = data.image && data.image !== '-'
            ? `<div class="w-full h-32 bg-gray-100 rounded-lg overflow-hidden mb-4">
                 ${data.image}
               </div>`
            : `<div class="w-full h-32 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                 <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                 </svg>
               </div>`;

        return `
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                ${imageHtml}

                <div class="space-y-3">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 mb-1">${data.name}</h3>
                        <p class="text-sm text-gray-600">${truncateText(data.description, 80)}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            ${data.category || 'Umum'}
                        </span>
                        <span class="text-lg font-bold text-green-600">${data.price}</span>
                    </div>
                </div>

                <div class="flex gap-2 mt-6 pt-4 border-t border-gray-200">
                    ${data.action}
                </div>
            </div>
        `;
    };

    const loadData = () => {
        if (isMobile) {
            $('#loading-state').removeClass('hidden');
            $('#mobile-cards').empty();
            $('#empty-state').addClass('hidden');

            $.get("{{ route('admin.menu.table') }}", function(response) {
                $('#loading-state').addClass('hidden');

                if (response.data && response.data.length > 0) {
                    const cards = response.data.map(item => createMobileCard(item)).join('');
                    $('#mobile-cards').html(cards);
                } else {
                    $('#empty-state').removeClass('hidden');
                }
            }).fail(function() {
                $('#loading-state').addClass('hidden');
                toastr.error('Gagal memuat data menu');
            });
        } else {
            if (table) {
                table.ajax.reload();
            }
        }
    };

    const initializeTable = () => {
        if (!isMobile) {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.menu.table') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'px-6 py-3 whitespace-nowrap text-sm text-gray-900'
                    },
                    {
                        data: 'name',
                        className: 'px-6 py-3 text-sm font-semibold text-gray-900'
                    },
                    {
                        data: 'description',
                        className: 'px-6 py-3 text-sm text-gray-600 max-w-xs truncate'
                    },
                    {
                        data: 'category',
                        className: 'px-6 py-3 whitespace-nowrap text-sm'
                    },
                    {
                        data: 'price',
                        className: 'px-6 py-3 whitespace-nowrap text-sm font-bold text-green-600'
                    },
                    {
                        data: 'image',
                        orderable: false,
                        searchable: false,
                        className: 'px-6 py-4 whitespace-nowrap text-sm'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'px-6 py-4 whitespace-nowrap text-sm'
                    }
                ],
                responsive: false,
                dom: '<"flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4"<"mb-4 lg:mb-0"l><"flex items-center space-x-4"f>>rtip',
                language: {
                    search: '',
                    searchPlaceholder: 'Cari menu...',
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
                    processing: '<div class="flex items-center justify-center py-8"><div class="flex items-center space-x-3"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-500"></div><span class="text-gray-600">Memuat data...</span></div></div>'
                }
            });
        } else {
            loadData();
        }
    };

    $(function() {
        // Check screen size and initialize accordingly
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

        // Form submissions
        $('#form_edit').submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let btn = $('#form_edit button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...');

            $.ajax({
                url: "{{ route('admin.menu.update') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#modal_edit').modal('hide');
                    loadData();
                    toastr.success(res.message, 'Berhasil 🚀');
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Perbarui');
                },
                error: function(xhr) {
                    console.log(xhr);
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
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Perbarui');
                }
            });
        });

        $('#form_add').submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let btn = $('#form_add button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');

            $.ajax({
                url: "{{ route('admin.menu.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#modal_add').modal('hide');
                    loadData();
                    toastr.success(res.message, 'Berhasil 🚀');
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan');
                },
                error: function(xhr) {
                    console.log(xhr);
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
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan');
                }
            });
        });

        // Modal events
        $('#modal_add').on('show.bs.modal', function() {
            $('#form_add button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan');
        });

        $('#modal_add').on('hidden.bs.modal', function() {
            $('#form_add')[0].reset();
        });

        $('#modal_edit').on('hidden.bs.modal', function() {
            // Reset button state when modal is hidden
            $('#form_edit button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Perbarui');
        });
    });
</script>

<style>
    /* Custom DataTable styling */
    .dataTables_wrapper {
        font-family: inherit;
    }

    .dataTables_filter input {
        @apply border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200;
    }

    .dataTables_length select {
        @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200;
    }

    .dataTables_info {
        @apply text-gray-600 text-sm;
    }

    .dataTables_paginate .paginate_button {
        @apply px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200;
    }

    .dataTables_paginate .paginate_button.current {
        @apply bg-green-500 text-white border-green-500;
    }

    .dataTables_paginate .paginate_button.disabled {
        @apply text-gray-400 cursor-not-allowed hover:bg-white;
    }

    /* Mobile card hover effects */
    .bg-gradient-to-br:hover {
        @apply shadow-lg;
    }

    /* Custom scrollbar for mobile view */
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

    /* Mobile button adjustments */
    @media (max-width: 640px) {
        .btn-sm {
            @apply px-2 py-1 text-xs;
        }

        .btn-sm svg {
            @apply w-3 h-3;
        }
    }

    /* Image styling in mobile cards */
    .mobile-card-image {
        @apply object-cover w-full h-full;
    }

    /* Truncate long text in mobile cards */
    .truncate-mobile {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
