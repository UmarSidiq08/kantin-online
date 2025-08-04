@extends('layouts.admin')

@section('title', 'Daftar Pesanan')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Pesanan</h1>
            <p class="text-gray-600 mt-1">Kelola semua pesanan yang masuk</p>
        </div>

        <!-- Orders Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($orders as $order)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 flex flex-col h-full">
                    <!-- Card Header -->
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">{{ $order->user->name }}</h3>
                            <span class="text-sm text-gray-500 bg-white px-2 py-1 rounded-full">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 flex flex-col flex-1">
                        <!-- Order Items -->
                        <div class="mb-4 flex-1">
                            <div class="bg-gray-50 rounded-lg h-32 overflow-y-auto border">
                                @foreach ($order->items as $item)
                                    <div class="flex justify-between items-center py-2 px-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                        <div class="flex-1">
                                            <span class="text-sm text-gray-800">{{ $item->menu->name }}</span>
                                            <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded-full ml-1">× {{ $item->quantity }}</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($item->menu->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="bg-blue-50 rounded-lg p-3 mb-4 border border-blue-100">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Total Pesanan</span>
                                <span class="text-lg font-bold text-blue-600">
                                    Rp {{ number_format($order->items->sum(fn($item) => $item->menu->price * $item->quantity), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            @if ($order->status === \App\Constant::ORDER_STATUS['PENDING'])
                                <div class="space-y-3">
                                    <!-- Accept Button -->
                                    @if ($order->payment_method === 'cash' && $order->payment_status === 'unpaid' && $order->status === 'pending')
                                        <!-- Tombol untuk membuka modal cash -->
                                        <button type="button"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg text-sm font-semibold transition-colors duration-200 flex items-center justify-center space-x-2"
                                            data-bs-toggle="modal" data-bs-target="#confirmCashModal-{{ $order->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>Terima Pesanan</span>
                                        </button>

                                        <!-- Modal cash -->
                                        <div class="modal fade" id="confirmCashModal-{{ $order->id }}" tabindex="-1" aria-labelledby="confirmCashModalLabel-{{ $order->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-lg border-0 shadow-lg">
                                                    <div class="modal-header bg-blue-600 text-white rounded-t-lg">
                                                        <h5 class="modal-title font-semibold" id="confirmCashModalLabel-{{ $order->id }}">
                                                            Konfirmasi Pembayaran Tunai
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body py-6 text-center">
                                                        <div class="mb-4">
                                                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                                </svg>
                                                            </div>
                                                            <p class="text-gray-700">Apakah siswa <strong>{{ $order->user->name }}</strong> sudah membayar secara tunai?</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 px-6 pb-6 pt-0 gap-3">
                                                        <!-- Tombol: Belum Bayar -->
                                                        <form method="POST" action="{{ route('admin.orders.mark-processed-cash', $order->id) }}" class="flex-1">
                                                            @csrf
                                                            <input type="hidden" name="bayar" value="no">
                                                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2.5 px-4 rounded-lg font-medium transition-colors duration-200">
                                                                Belum Bayar
                                                            </button>
                                                        </form>

                                                        <!-- Tombol: Sudah Bayar -->
                                                        <form method="POST" action="{{ route('admin.orders.mark-processed-cash', $order->id) }}" class="flex-1">
                                                            @csrf
                                                            <input type="hidden" name="bayar" value="yes">
                                                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 rounded-lg font-medium transition-colors duration-200">
                                                                Sudah Bayar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Tombol terima langsung -->
                                        <form method="POST" action="{{ route('admin.orders.mark-processed', $order->id) }}" onsubmit="return confirm('Terima pesanan ini dan tandai sebagai DIPROSES?')" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg text-sm font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Terima Pesanan</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Reject Button -->
                                    <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST" onsubmit="return confirm('Tolak pesanan ini?')" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg text-sm font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>Tolak Pesanan</span>
                                        </button>
                                    </form>
                                </div>

                            @elseif ($order->status === \App\Constant::ORDER_STATUS['DIPROSES'])
                                <!-- Complete Button -->
                                <form action="{{ route('admin.orders.complete-and-delete', $order->id) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini sebagai SELESAI dan hapus dari daftar admin?')" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg text-sm font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Tandai Selesai</span>
                                    </button>
                                </form>

                            @elseif ($order->status === \App\Constant::ORDER_STATUS['DITOLAK'])
                                <div class="space-y-3">
                                    <!-- Status Badge -->
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <span class="text-red-800 font-semibold">Pesanan Ditolak</span>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.orders.rejected-delete', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan yang ditolak ini dari daftar admin?')" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg text-sm font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span>Hapus dari Daftar</span>
                                        </button>
                                    </form>
                                </div>

                            @elseif ($order->status === \App\Constant::ORDER_STATUS['SELESAI'])
                                <!-- Status Badge -->
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-green-800 font-semibold">Pesanan Selesai</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            @empty
                <!-- Empty State -->
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pesanan</h3>
                        <p class="text-gray-500">Pesanan baru akan muncul di sini ketika ada yang memesan.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

@endsection
