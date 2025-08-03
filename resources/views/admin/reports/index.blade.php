{{-- @extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Laporan Penjualan</h4>

            <form method="GET" class="row g-3 mb-4" id="filter-form">
                <div class="col-md-4">
                    <label for="start_date">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" id="start_date">
                </div>
                <div class="col-md-4">
                    <label for="end_date">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control" id="end_date">

                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>

            <table id="laporanTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Pemesan</th>
                        <th>Status</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function() {
            let table = $('#laporanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.report.data') }}',
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [{
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'nama_pemesan',
                        name: 'nama_pemesan'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'total_harga',
                        name: 'total_harga'
                    }
                ]
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });
        });
    </script>
@endpush --}}
