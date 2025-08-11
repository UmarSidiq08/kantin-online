@extends('layouts.admin')

@section('title', 'Upgrade ke Premium')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">

            <!-- Header Section -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-100 rounded-full mb-3">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Upgrade ke Premium</h1>
                <p class="text-base lg:text-lg text-gray-600">Dapatkan akses ke fitur laporan penjualan dan analisis bisnis
                </p>
            </div>

            @if (session('error'))
                <div class="mb-4 max-w-4xl mx-auto">
                    <div class="bg-red-50 border-l-4 border-red-400 p-3 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-4 w-4 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-2">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 lg:gap-6">

                <!-- Premium Features -->
                <div class="xl:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden h-full">
                        <div class="bg-blue-600 px-4 lg:px-6 py-3 lg:py-4">
                            <h2 class="text-lg lg:text-xl font-semibold text-white">Fitur Premium</h2>
                        </div>

                        <div class="p-4 lg:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    $features = [
                                        [
                                            'icon' =>
                                                'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                                            'title' => 'Laporan Penjualan',
                                            'description' => 'Lihat laporan penjualan dengan grafik dan filter tanggal',
                                        ],
                                        [
                                            'icon' =>
                                                'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                            'title' => 'Export Data',
                                            'description' => 'Export laporan ke Excel dan PDF untuk arsip',
                                        ],
                                        [
                                            'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
                                            'title' => 'Grafik Analytics',
                                            'description' => 'Visualisasi data penjualan dengan grafik interaktif',
                                        ],
                                        [
                                            'icon' =>
                                                'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                                            'title' => 'Filter Periode',
                                            'description' => 'Filter laporan berdasarkan tanggal dan periode',
                                        ],
                                        [
                                            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                            'title' => 'Data Real-time',
                                            'description' => 'Akses data penjualan secara real-time dan up-to-date',
                                        ],
                                        [
                                            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                            'title' => 'Analisis Mendalam',
                                            'description' => 'Insight bisnis dengan analisis data yang komprehensif',
                                        ],
                                    ];
                                @endphp

                                @foreach ($features as $feature)
                                    <div
                                        class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 lg:w-10 lg:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 lg:w-5 lg:h-5 text-blue-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="{{ $feature['icon'] }}"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-sm lg:text-base mb-1">
                                                {{ $feature['title'] }}</h3>
                                            <p class="text-gray-600 text-xs lg:text-sm">{{ $feature['description'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Card -->
                <div class="xl:col-span-1">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-blue-200">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 lg:px-6 py-6 text-center">
                            <h3 class="text-lg lg:text-xl font-semibold text-white mb-3">Premium Plan</h3>
                            <div class="flex items-center justify-center mb-2">
                                <span class="text-2xl lg:text-3xl font-bold text-white">Rp49.900</span>
                            </div>
                            <p class="text-blue-100 text-xs lg:text-sm">Pembayaran satu kali</p>
                        </div>

                        <div class="p-4 lg:p-6">
                            <div class="space-y-3 mb-6">
                                @php
                                    $benefits = [
                                        'Akses laporan penjualan',
                                        'Export Excel & PDF',
                                        'Grafik analytics',
                                        'Filter periode custom',
                                        'Data real-time',
                                        'Analisis mendalam',
                                    ];
                                @endphp

                                @foreach ($benefits as $benefit)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-700 text-sm font-medium">{{ $benefit }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <button id="pay-button"
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl text-sm lg:text-base">
                                <span class="flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span>Upgrade Sekarang</span>
                                </span>
                            </button>

                            <div class="text-center mt-4">
                                <div class="flex items-center justify-center space-x-2 text-xs lg:text-sm text-gray-500">
                                    <svg class="w-3 h-3 lg:w-4 lg:h-4 text-green-500" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Pembayaran aman</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        fetch("{{ route('admin.premium.pay') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            snap.pay(data.token, {
                onSuccess: function (result) {
                    alert("Pembayaran berhasil. Silakan tunggu aktivasi.");
                    location.href = "/admin/laporan-penjualan";
                },
                onPending: function (result) {
                    alert("Transaksi sedang diproses.");
                },
                onError: function (result) {
                    alert("Pembayaran gagal.");
                },
                onClose: function() {
                    alert("Transaksi dibatalkan.");
                }
            });
        })
        .catch(error => {
            alert("Gagal membuat transaksi.");
            console.error(error);
        });
    });
</script>
@endpush
