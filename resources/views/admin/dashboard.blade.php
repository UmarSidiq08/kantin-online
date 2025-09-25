@extends('layouts.admin')

@section('title', 'Dashboard Admin Kantin')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-600 mt-1">Selamat datang kembali! Berikut adalah ringkasan bisnis Anda hari ini.</p>
    </div>

    <!-- First Row: Total Orders + Total Revenue -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 flex-1">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pesanan</h3>
                    <div class="flex items-baseline mt-2">
                        <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
                        <p class="ml-2 text-sm font-medium text-gray-400">pesanan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 flex-1">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</h3>
                    <div class="flex items-baseline mt-2">
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="ml-2 text-sm font-medium text-gray-400">IDR</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row: Revenue Breakdown by Payment Method -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 flex-1">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pendapatan Cash</h3>
                    <div class="flex items-baseline mt-2">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($cashRevenue, 0, ',', '.') }}</p>
                        <p class="ml-2 text-sm font-medium text-gray-400">IDR</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $totalRevenue > 0 ? number_format(($cashRevenue / $totalRevenue) * 100, 1) : '0' }}% dari total
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 flex-1">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pendapatan Digital</h3>
                    <div class="flex items-baseline mt-2">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($digitalRevenue, 0, ',', '.') }}</p>
                        <p class="ml-2 text-sm font-medium text-gray-400">IDR</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $totalRevenue > 0 ? number_format(($digitalRevenue / $totalRevenue) * 100, 1) : '0' }}% dari total
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0-9c-5 0-9 4-9 9s4 9 9 9">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 flex-1">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pendapatan Saldo</h3>
                    <div class="flex items-baseline mt-2">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($balanceRevenue, 0, ',', '.') }}</p>
                        <p class="ml-2 text-sm font-medium text-gray-400">IDR</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $totalRevenue > 0 ? number_format(($balanceRevenue / $totalRevenue) * 100, 1) : '0' }}% dari total
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Third Row: Top Menu -->
    <div class="grid grid-cols-1 gap-6 mb-8">
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Menu Terlaris</h3>
                    <div class="space-y-3">
                        @forelse ($topMenus->take(3) as $index => $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3 min-w-0 flex-1">
                                    <span
                                        class="flex-shrink-0 w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center text-xs font-semibold text-gray-600">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900 truncate">{{ $item->menu->name }}</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-500 ml-2">{{ $item->total_qty }}x</span>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Tidak ada data</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Analitik Menu Terlaris</h2>
                    <p class="text-sm text-gray-500 mt-1">Grafik performa menu berdasarkan jumlah pesanan</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="relative" style="height: 400px;">
                <canvas id="topMenusChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('topMenusChart').getContext('2d');

        const topMenusChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($menuNames) !!},
                datasets: [{
                    data: {!! json_encode($menuQuantities) !!},
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                        '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6366f1'
                    ],
                    borderColor: [
                        '#2563eb', '#059669', '#d97706', '#dc2626', '#7c3aed',
                        '#0891b2', '#65a30d', '#ea580c', '#db2777', '#4f46e5'
                    ],
                    borderWidth: 1,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#f9fafb',
                        bodyColor: '#f9fafb',
                        borderColor: '#374151',
                        borderWidth: 1,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12,
                            },
                            maxRotation: 45,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12,
                            },
                            padding: 8,
                        }
                    }
                },
                elements: {
                    bar: {
                        borderRadius: 4,
                    }
                }
            }
        });
    </script>
@endpush
