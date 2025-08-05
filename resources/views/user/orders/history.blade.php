@extends('layouts.user')

@section('title', 'Riwayat Pesanan')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container mt-4">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Riwayat Pesanan</h2>
        </div>
        <table class="table table-bordered mt-3" id="orders-table">
            <thead>
                <tr>
                    <th class="px-1 py-3 text-center text-sm font-medium text-gray-700">No</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Tanggal</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Menu</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Total</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Pembayaran</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Status Bayar</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

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
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'menus',
                        name: 'menus',
                        orderable: false,
                        searchable: false
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
                        name: 'total_price'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
                    },

                ]
            });
        });
    </script>
@endpush
