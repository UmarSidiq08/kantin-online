@extends('layouts.user')

@section('title', 'Dashboard Siswa')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <div class="container mx-auto px-6 py-12">
            <div class="text-center mb-12">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h1
                    class="text-4xl font-bold bg-gradient-to-r from-gray-800 via-gray-700 to-gray-800 bg-clip-text text-transparent mb-3 leading-tight">
                    Selamat Datang, {{ auth()->user()->name }}
                </h1>
                <p class="text-lg text-gray-600 mb-4">Kelola pesanan makananmu dengan mudah</p>
                <div class="mt-4 w-20 h-1 bg-gradient-to-r from-blue-500 to-indigo-500 mx-auto rounded-full shadow-sm">
                </div>
            </div>

            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Pilih Kantin
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                {{--
    Ganti bagian loop @foreach ($canteensWithStatus as $canteen) di dashboard user
    Pastikan controller dashboard sudah pass info is_blocked ke canteensWithStatus
--}}

                @foreach ($canteensWithStatus as $canteen)
                    @php
                        $isBlocked = $canteen['is_blocked'] ?? false;
                        $isAccessible = $canteen['is_open'] && !$isBlocked;
                    @endphp

                    <div
                        class="relative group bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50
        {{ $isBlocked ? 'opacity-80 border-red-200/50' : (!$canteen['is_open'] ? 'opacity-80 grayscale-[0.3]' : 'hover:shadow-xl hover:shadow-blue-100/50 hover:border-blue-300/30') }}
        transition-all duration-500 overflow-hidden transform {{ $isAccessible ? 'hover:-translate-y-1' : '' }}">

                        <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-transparent pointer-events-none">
                        </div>

                        {{-- Badge status --}}
                        <div class="absolute top-4 right-4 z-10">
                            @if ($isBlocked)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold shadow-sm bg-red-100 text-red-800 border border-red-200">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    Diblokir
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold shadow-sm bg-{{ $canteen['status_color'] }}-100 text-{{ $canteen['status_color'] }}-800 border border-{{ $canteen['status_color'] }}-200">
                                    <span
                                        class="w-2 h-2 bg-{{ $canteen['status_color'] }}-500 rounded-full mr-2 {{ $canteen['is_open'] ? 'animate-pulse' : '' }}"></span>
                                    {{ $canteen['status_text'] }}
                                </span>
                            @endif
                        </div>

                        {{-- Wrap link hanya jika bisa diakses --}}
                        @if ($isAccessible)
                            <a href="{{ route('user.pilih-kantin', $canteen['id']) }}" class="block relative z-10">
                        @endif

                        <div class="p-6 pb-4 relative">
                            <div class="relative mb-4">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br
                    {{ $isBlocked ? 'from-red-400 to-red-600' : 'from-blue-500 via-blue-600 to-indigo-600' }}
                    rounded-2xl flex items-center justify-center
                    {{ $isAccessible ? 'group-hover:scale-110 group-hover:rotate-3' : '' }}
                    transition-all duration-500 shadow-lg">
                                    @if ($isBlocked)
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-white drop-shadow-sm" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            <h4
                                class="text-xl font-semibold mb-2
                {{ $isBlocked ? 'text-red-700' : ($isAccessible ? 'text-gray-800 group-hover:text-blue-600' : 'text-gray-500') }}
                transition-colors duration-300">
                                {{ $canteen['name'] }}
                            </h4>

                            @if ($isBlocked)
                                {{-- Pesan blokir --}}
                                <div
                                    class="flex items-start gap-2 bg-red-50 border border-red-200 rounded-lg px-3 py-2 mb-4">
                                    <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                    </svg>
                                    <p class="text-red-700 text-xs font-medium">Kamu diblokir oleh kantin ini. Hubungi
                                        pemilik kantin untuk informasi lebih lanjut.</p>
                                </div>
                                <div class="flex items-center text-red-400 text-sm font-medium cursor-not-allowed">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span>Akses Ditolak</span>
                                </div>
                            @elseif ($canteen['is_open'])
                                <p class="text-gray-600 text-sm mb-2 font-medium">Lihat menu dan pesan makanan</p>
                                @if ($canteen['schedule_text'])
                                    <div
                                        class="flex items-center text-green-600 text-xs mb-4 bg-green-50 px-2 py-1 rounded-lg border border-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $canteen['schedule_text'] }}
                                    </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <div
                                        class="flex items-center text-blue-600 text-sm font-semibold group-hover:text-blue-700">
                                        <span class="mr-2">Pilih Kantin</span>
                                        <svg class="w-4 h-4 transform group-hover:translate-x-2 transition-transform duration-300"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <p class="text-red-500 text-sm mb-2 font-medium">Kantin sedang tutup</p>
                                @if ($canteen['schedule_text'])
                                    <div
                                        class="flex items-center text-gray-500 text-xs mb-4 bg-gray-50 px-2 py-1 rounded-lg border border-gray-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $canteen['schedule_text'] }}
                                    </div>
                                @endif
                                <div class="flex items-center text-gray-400 text-sm font-medium cursor-not-allowed">
                                    <span class="mr-2">Kantin Tutup</span>
                                </div>
                            @endif
                        </div>

                        @if ($isAccessible)
                            </a>
                        @endif

                        <div
                            class="absolute bottom-0 left-0 right-0 h-1
            {{ $isBlocked ? 'bg-gradient-to-r from-red-400 to-red-500' : 'bg-gradient-to-r from-blue-500 to-indigo-500' }}
            transform scale-x-0 {{ $isAccessible ? 'group-hover:scale-x-100' : '' }} transition-transform duration-500 origin-left">
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12 p-6 bg-white/50 backdrop-blur-sm rounded-2xl border border-gray-200/50">
                <div class="flex items-center justify-center text-gray-600 text-sm">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Klik pada kantin yang buka untuk melihat menu dan melakukan pemesanan
                </div>
            </div>
        </div>
    </div>
@endsection
