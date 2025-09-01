@extends('layouts.admin')

@section('title', 'Daftar Pesanan')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Pesanan</h1>
            <p class="text-gray-600 mt-1">Kelola semua pesanan yang masuk</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="orders-container">
            @forelse ($orders as $order)
                <div class="@if (
                    ($order->payment_method === 'digital' && $order->payment_status === 'paid') ||
                        ($order->payment_method === 'cash' && $order->payment_status === 'paid')) bg-green-50 @elseif($order->payment_method === 'cash' && $order->payment_status === 'unpaid') bg-yellow-50 @else bg-white @endif rounded-lg shadow-sm border-2
                    @if (
                        ($order->payment_method === 'digital' && $order->payment_status === 'paid') ||
                            ($order->payment_method === 'cash' && $order->payment_status === 'paid')) border-green-400 @elseif($order->payment_method === 'cash' && $order->payment_status === 'unpaid') border-yellow-400 @else border-gray-200 @endif hover:shadow-md transition-all duration-200 flex flex-col h-full"
                    data-order-id="{{ $order->id }}">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">{{ $order->user->name }}</h3>
                            <span
                                class="text-sm text-gray-500 bg-white px-2 py-1 rounded-full">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="p-4 flex flex-col flex-1">
                        <div class="mb-4 flex-1">
                            <div class="bg-gray-50 rounded-lg h-32 overflow-y-auto border">
                                @foreach ($order->items as $item)
                                    <div
                                        class="flex justify-between items-center py-2 px-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                        <div class="flex-1">
                                            <span class="text-sm text-gray-800">{{ $item->menu->name }}</span>
                                            <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded-full ml-1">×
                                                {{ $item->quantity }}</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($item->menu->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-3 mb-4 border border-blue-100">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Total Pesanan</span>
                                <span class="text-lg font-bold text-blue-600">
                                    Rp
                                    {{ number_format($order->items->sum(fn($item) => $item->menu->price * $item->quantity), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-auto">
                            @if ($order->status === \App\Constant::ORDER_STATUS['PENDING'])
                                <div class="space-y-3">
                                    {{-- Semua pesanan PENDING menggunakan tombol yang sama --}}
                                    <button type="button" id="accept-order-{{ $order->id }}"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg text-sm font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                                        @include('partials.icons.check')
                                        <span>Terima Pesanan</span>
                                    </button>

                                    <button type="button" id="reject-order-{{ $order->id }}"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg text-sm font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                                        @include('partials.icons.x')
                                        <span>Tolak Pesanan</span>
                                    </button>
                                </div>
                            @elseif ($order->status === \App\Constant::ORDER_STATUS['DIPROSES'])
                                <button type="button" id="complete-order-{{ $order->id }}"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg text-sm font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                                    @include('partials.icons.check-simple')
                                    <span>Tandai Selesai</span>
                                </button>
                            @elseif ($order->status === \App\Constant::ORDER_STATUS['DITOLAK'])
                                <div class="space-y-3">
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            @include('partials.icons.warning')
                                            <span class="text-red-800 font-semibold">Pesanan Ditolak</span>
                                        </div>
                                    </div>

                                    <button type="button" id="delete-rejected-{{ $order->id }}"
                                        class="w-full bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg text-sm font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                                        @include('partials.icons.trash')
                                        <span>Hapus dari Daftar</span>
                                    </button>
                                </div>
                            @elseif ($order->status === \App\Constant::ORDER_STATUS['SELESAI'])
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        @include('partials.icons.check-circle')
                                        <span class="text-green-800 font-semibold">Pesanan Selesai</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            @include('partials.icons.clipboard')
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pesanan</h3>
                        <p class="text-gray-500">Pesanan baru akan muncul di sini ketika ada yang memesan.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-3"></div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Helper Functions
            function showLoading() {
                $('#loading-overlay').removeClass('hidden');
            }

            function hideLoading() {
                $('#loading-overlay').addClass('hidden');
            }

            function showAlert(message, type = 'success') {
                const toastClass = type === 'success' ?
                    'bg-green-500 border border-green-600 text-white' :
                    'bg-red-500 border border-red-600 text-white';

                const toast = $(`
                <div class="${toastClass} border px-4 py-3 rounded shadow-md w-72 flex justify-between items-center">
                    <span class="text-sm font-medium">${message}</span>
                    <button class="ml-4 font-bold text-xl leading-none" onclick="$(this).parent().remove()">×</button>
                    </div>`);

                $('#toast-container').append(toast);
                setTimeout(() => toast.fadeOut(300, () => toast.remove()), 4000);
            }

            function removeOrderCard(orderId) {
                $(`[data-order-id="${orderId}"]`).fadeOut(300, function() {
                    $(this).remove();
                    // Check if no orders left
                    if ($('#orders-container [data-order-id]').length === 0) {
                        location.reload();
                    }
                });
            }

            // Accept Cash Order - Langsung selesaikan dan tandai paid
            $(document).on('click', '[id^="accept-cash-order-"]', function() {
                const orderId = $(this).attr('id').split('-')[3];

                if (!confirm('Terima pesanan ini dan langsung tandai sebagai SELESAI & DIBAYAR?')) return;

                showLoading();
                $.ajax({
                    url: `/admin/orders/${orderId}/complete-cash-directly`,
                    type: 'POST',
                    success: function(response) {
                        hideLoading();
                        showAlert('Pesanan berhasil diterima dan diselesaikan');
                        removeOrderCard(orderId);
                    },
                    error: function(xhr) {
                        hideLoading();
                        showAlert('Terjadi kesalahan saat memproses pesanan', 'error');
                    }
                });
            });

            // Accept Order - Normal flow (digital/cash paid)
            $(document).on('click', '[id^="accept-order-"]', function() {
                const orderId = $(this).attr('id').split('-')[2];

                if (!confirm('Terima pesanan ini dan tandai sebagai DIPROSES?')) return;

                showLoading();
                $.ajax({
                    url: `/admin/orders/${orderId}/accept`,
                    type: 'POST',
                    success: function(response) {
                        hideLoading();
                        showAlert('Pesanan berhasil diterima dan ditandai sebagai diproses');
                        location.reload();
                    },
                    error: function(xhr) {
                        hideLoading();
                        showAlert('Terjadi kesalahan saat memproses pesanan', 'error');
                    }
                });
            });

            // Reject Order
            $(document).on('click', '[id^="reject-order-"]', function() {
                const orderId = $(this).attr('id').split('-')[2];

                if (!confirm('Tolak pesanan ini?')) return;

                showLoading();
                $.ajax({
                    url: `/admin/orders/${orderId}/reject`,
                    type: 'POST',
                    success: function(response) {
                        hideLoading();
                        showAlert('Pesanan berhasil ditolak');
                        location.reload();
                    },
                    error: function(xhr) {
                        hideLoading();
                        showAlert('Terjadi kesalahan saat menolak pesanan', 'error');
                    }
                });
            });

            // Complete Order
            $(document).on('click', '[id^="complete-order-"]', function() {
                const orderId = $(this).attr('id').split('-')[2];

                if (!confirm('Tandai pesanan ini sebagai SELESAI dan hapus dari daftar admin?')) return;

                showLoading();
                $.ajax({
                    url: `/admin/orders/${orderId}/complete`,
                    type: 'POST',
                    success: function(response) {
                        hideLoading();
                        showAlert('Pesanan berhasil diselesaikan');
                        removeOrderCard(orderId);
                    },
                    error: function(xhr) {
                        hideLoading();
                        showAlert('Terjadi kesalahan saat menyelesaikan pesanan', 'error');
                    }
                });
            });

            // Delete Rejected Order
            $(document).on('click', '[id^="delete-rejected-"]', function() {
                const orderId = $(this).attr('id').split('-')[2];

                if (!confirm('Yakin ingin menghapus pesanan yang ditolak ini dari daftar admin?')) return;

                showLoading();
                $.ajax({
                    url: `/admin/orders/rejected-delete/${orderId}`,
                    type: 'POST',
                    success: function(response) {
                        hideLoading();
                        showAlert('Pesanan yang ditolak berhasil dihapus');
                        removeOrderCard(orderId);
                    },
                    error: function(xhr) {
                        hideLoading();
                        showAlert('Terjadi kesalahan saat menghapus pesanan', 'error');
                    }
                });
            });
        });
    </script>
@endpush
