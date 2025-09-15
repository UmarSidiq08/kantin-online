@extends('layouts.admin')

@section('title', 'Kelola Diskon Menu')
@section('page-title', 'Kelola Diskon Menu')

@section('modal')
    @include('admin.discounts.modal.add')
    @include('admin.discounts.modal.edit')
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Kelola Diskon Menu</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
                <i class="fas fa-plus me-2"></i>Tambah Diskon
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Menu</th>
                            <th>Harga Asli</th>
                            <th>Diskon</th>
                            <th>Harga Setelah Diskon</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Penghematan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
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

        // Update value placeholder berdasarkan type
        updateValuePlaceholder('#modal_edit');
    };

    const onDelete = (id) => {
        if (confirm("Yakin ingin menghapus diskon ini?")) {
            $.post("{{ route('admin.discount.destroy') }}", {
                id
            }, function(res) {
                toastr.success(res.message, 'Berhasil');
                $('#table').DataTable().ajax.reload();
            }).fail(function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan');
            });
        }
    };

    const onToggleStatus = (id) => {
        $.post("{{ route('admin.discount.toggle-status') }}", {
            id
        }, function(res) {
            toastr.success(res.message, 'Berhasil');
            $('#table').DataTable().ajax.reload();
        }).fail(function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan');
        });
    };

    // Function to update value placeholder and validation
    const updateValuePlaceholder = (modalSelector) => {
        const typeSelect = $(modalSelector + ' select[name="type"]');
        const valueInput = $(modalSelector + ' input[name="value"]');

        if (typeSelect.val() === 'percentage') {
            valueInput.attr('placeholder', 'Contoh: 20 untuk 20%');
            valueInput.attr('max', '100');
            valueInput.next('.form-text').text('Masukkan nilai persentase (1-100)');
        } else {
            valueInput.attr('placeholder', 'Contoh: 5000 untuk Rp 5.000');
            valueInput.removeAttr('max');
            valueInput.next('.form-text').text('Masukkan nilai nominal dalam rupiah');
        }
    };

    // Function to get and display menu price
    const updateMenuPrice = (modalSelector) => {
        const menuId = $(modalSelector + ' select[name="menu_id"]').val();
        const priceDisplay = $(modalSelector + ' .menu-price-display');

        if (menuId) {
            $.get("{{ route('admin.discount.menu-price', ':id') }}".replace(':id', menuId), function(res) {
                priceDisplay.html('<strong>Harga Menu: ' + res.formatted_price + '</strong>').show();

                // Update max value untuk fixed discount
                if ($(modalSelector + ' select[name="type"]').val() === 'fixed') {
                    $(modalSelector + ' input[name="value"]').attr('max', res.price - 1);
                }
            });
        } else {
            priceDisplay.hide();
        }
    };

    $(function() {
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.discount.table') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'menu_name' },
                { data: 'original_price' },
                { data: 'discount_value' },
                { data: 'discounted_price' },
                { data: 'period', orderable: false },
                { data: 'status', orderable: false },
                { data: 'savings', orderable: false },
                { data: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']]
        });

        // Event handlers for type change
        $('#modal_add select[name="type"], #modal_edit select[name="type"]').change(function() {
            updateValuePlaceholder('#' + $(this).closest('.modal').attr('id'));
        });

        // Event handlers for menu change
        $('#modal_add select[name="menu_id"], #modal_edit select[name="menu_id"]').change(function() {
            updateMenuPrice('#' + $(this).closest('.modal').attr('id'));
        });

        // Form submissions
        $('#form_add').submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');

            $.ajax({
                url: "{{ route('admin.discount.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#modal_add').modal('hide');
                    $('#table').DataTable().ajax.reload(null, false);
                    toastr.success(res.message, 'Berhasil');
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan');
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
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan');
                }
            });
        });

        $('#form_edit').submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...');

            $.ajax({
                url: "{{ route('admin.discount.update') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#modal_edit').modal('hide');
                    $('#table').DataTable().ajax.reload(null, false);
                    toastr.success(res.message, 'Berhasil');
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Perbarui');
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
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Perbarui');
                }
            });
        });

        // Reset forms when modals are hidden
        $('#modal_add').on('hidden.bs.modal', function() {
            $('#form_add')[0].reset();
            $('.menu-price-display').hide();
        });

        $('#modal_edit').on('hidden.bs.modal', function() {
            $('.menu-price-display').hide();
        });

        // Initialize placeholders
        updateValuePlaceholder('#modal_add');
        updateValuePlaceholder('#modal_edit');
    });
</script>
@endsection
