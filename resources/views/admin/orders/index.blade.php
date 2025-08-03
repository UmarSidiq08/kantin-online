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
                            <span class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 flex flex-col flex-1">
                        <!-- Order Items -->
                        <div class="mb-4 flex-1">
                            <div class="bg-gray-50 rounded-lg h-32 overflow-y-auto">
                                @foreach ($order->items as $item)
                                    <div class="flex justify-between items-center py-2 px-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                        <div class="flex-1">
                                            <span class="text-sm text-gray-800">{{ $item->menu->name }}</span>
                                            <span class="text-xs text-gray-500 ml-1">× {{ $item->quantity }}</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($item->menu->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="bg-blue-50 rounded-lg p-3 mb-4">
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
                                <div class="space-y-2">
                                    <!-- Accept Button -->
                                    <form action="{{ route('admin.orders.mark-processed', $order->id) }}" method="POST"
                                          onsubmit="return confirm('Terima pesanan ini dan tandai sebagai DIPROSES?')" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                                            Terima Pesanan
                                        </button>
                                    </form>

                                    <!-- Reject Button -->
                                    <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST"
                                          onsubmit="return confirm('Tolak pesanan ini?')" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                                            Tolak Pesanan
                                        </button>
                                    </form>
                                </div>

                            @elseif ($order->status === \App\Constant::ORDER_STATUS['DIPROSES'])
                                <!-- Complete Button -->
                                <form action="{{ route('admin.orders.complete-and-delete', $order->id) }}" method="POST"
                                      onsubmit="return confirm('Tandai pesanan ini sebagai SELESAI dan hapus dari daftar admin?')" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                                        Tandai Selesai
                                    </button>
                                </form>

                            @elseif ($order->status === \App\Constant::ORDER_STATUS['DITOLAK'])
                                <div class="space-y-2">
                                    <!-- Status Badge -->
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-2.5 text-center">
                                        <span class="text-red-800 text-sm font-medium">Pesanan Ditolak</span>
                                    </div>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.orders.rejected-delete', $order->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus pesanan yang ditolak ini dari daftar admin?')" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                                            Hapus dari Daftar
                                        </button>
                                    </form>
                                </div>

                            @elseif ($order->status === \App\Constant::ORDER_STATUS['SELESAI'])
                                <!-- Status Badge -->
                                <div class="bg-green-50 border border-green-200 rounded-lg p-2.5 text-center">
                                    <span class="text-green-800 text-sm font-medium">Pesanan Selesai</span>
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
