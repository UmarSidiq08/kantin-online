@extends('layouts.admin')

@section('title', 'Master Menu')
@section('page-title', 'Data Menu')

@section('modal')
    @include('admin.menus.modal.add')
    @include('admin.menus.modal.edit')
@endsection
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Data Menu</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">Tambah Menu</button>
        </div>
        <div class="card-body">
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
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

        const onEdit = (id, name, description, price) => {
            $('#modal_edit').modal('show');
            $('#modal_edit input[name="id"]').val(id);
            $('#modal_edit input[name="name"]').val(name);
            $('#modal_edit textarea[name="description"]').val(description);
            $('#modal_edit select[name="category"]').val(category);
            $('#modal_edit input[name="price"]').val(price);
        } //menampilkan modal edit dan mengisi datanya

        const onDelete = (id) => {
            if (confirm("Yakin ingin menghapus?")) {
                $.post("{{ route('admin.menu.destroy') }}", {
                    id
                }, function(res) {
                    toastr.success(res.message, 'Berhasil 🚀');
                    $('#table').DataTable().ajax.reload();
                });
            }
        } //menghapus menu berdasarkan

        $(function() {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.menu.table') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'category'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            }); //untuk menampilkan data table

            $('#form_edit').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let btn = $('#form_edit button[type="submit"]');

                $.ajax({
                    url: "{{ route('admin.menu.update') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#modal_edit').modal('hide'); // tutup modal
                        $('#table').DataTable().ajax.reload(null,
                            false); // reload table tanpa reset paging
                        toastr.success(data.message, 'Berhasil 🚀');
                    },
                    error: function(err) {
                        console.log(err);
                        alert('Terjadi kesalahan.');
                        btn.prop('disabled', false).text('Simpan'); // reset tombol
                    }
                });
            });
            $('#form_add').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let btn = $('#form_add button[type="submit"]');
                btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('admin.menu.store') }}", //kirim
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        $('#modal_add').modal('hide');
                        $('#table').DataTable().ajax.reload(null, false);
                        toastr.success(res.message, 'Berhasil 🚀');
                        btn.prop('disabled', false).text('Simpan');
                    },
                    error: function(err) {
                        console.log(err);
                        toastr.error('Terjadi kesalahan saat menyimpan.');
                        btn.prop('disabled', false).text('Simpan');
                    }
                });
                $('#modal_add').on('show.bs.modal', function() {
                    $('#form_add button[type="submit"]').prop('disabled', false).text('Simpan');
                });

                $('#modal_add').on('hidden.bs.modal', function() {
                    $('#form_add')[0].reset();
                });
            });

        });
    </script>

@endsection
