@extends('layouts.user')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="container mx-auto px-6 py-12">
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-6 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-800 via-gray-700 to-gray-800 bg-clip-text text-transparent mb-3 leading-tight">
                Selamat Datang, {{ auth()->user()->name }}
            </h1>
            <p class="text-lg text-gray-600 mb-4">Kelola pesanan makananmu dengan mudah</p>
            <div class="mt-4 w-20 h-1 bg-gradient-to-r from-blue-500 to-indigo-500 mx-auto rounded-full shadow-sm"></div>
        </div>

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Pilih Kantin
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach ($canteensWithStatus as $canteen)
                <div class="relative group bg-white/70 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50 hover:shadow-xl hover:shadow-blue-100/50 hover:border-blue-300/30 transition-all duration-500 overflow-hidden transform hover:-translate-y-1 {{ !$canteen['is_open'] ? 'opacity-80 grayscale-[0.3]' : '' }}">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/50 to-transparent pointer-events-none"></div>
                    <div class="absolute top-4 right-4 z-10">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold shadow-sm bg-{{ $canteen['status_color'] }}-100 text-{{ $canteen['status_color'] }}-800 border border-{{ $canteen['status_color'] }}-200">
                            <span class="w-2 h-2 bg-{{ $canteen['status_color'] }}-500 rounded-full mr-2 animate-pulse"></span>
                            {{ $canteen['status_text'] }}
                        </span>
                    </div>

                    @if($canteen['is_open'])
                        <a href="{{ route('user.pilih-kantin', $canteen['id']) }}" class="block relative z-10">
                    @endif

                    <div class="p-6 pb-4 relative">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center {{ $canteen['is_open'] ? 'group-hover:scale-110 group-hover:rotate-3' : '' }} transition-all duration-500 shadow-lg">
                                <svg class="w-8 h-8 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>

                        <h4 class="text-xl font-semibold text-gray-800 mb-2 {{ $canteen['is_open'] ? 'group-hover:text-blue-600' : '' }} transition-colors duration-300">
                            {{ $canteen['name'] }}
                        </h4>

                        @if($canteen['is_open'])
                            <p class="text-gray-600 text-sm mb-2 font-medium">Lihat menu dan pesan makanan</p>
                            @if($canteen['schedule_text'])
                                <div class="flex items-center text-green-600 text-xs mb-4 bg-green-50 px-2 py-1 rounded-lg border border-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $canteen['schedule_text'] }}
                                </div>
                            @endif
                        @else
                            <p class="text-red-500 text-sm mb-2 font-medium">Kantin sedang tutup</p>
                            @if($canteen['schedule_text'])
                                <div class="flex items-center text-gray-500 text-xs mb-4 bg-gray-50 px-2 py-1 rounded-lg border border-gray-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $canteen['schedule_text'] }}
                                </div>
                            @endif
                        @endif

                        @if($canteen['is_open'])
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-blue-600 text-sm font-semibold group-hover:text-blue-700">
                                    <span class="mr-2">Pilih Kantin</span>
                                    <svg class="w-4 h-4 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                                <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-300">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6.001"/>
                                    </svg>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-400 text-sm font-medium cursor-not-allowed">
                                    <span class="mr-2">Kantin Tutup</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                    </svg>
                                </div>
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($canteen['is_open'])
                        </a>
                    @endif

                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12 p-6 bg-white/50 backdrop-blur-sm rounded-2xl border border-gray-200/50">
            <div class="flex items-center justify-center text-gray-600 text-sm">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Klik pada kantin yang buka untuk melihat menu dan melakukan pemesanan
            </div>
        </div>
    </div>
</div>
@endsection
