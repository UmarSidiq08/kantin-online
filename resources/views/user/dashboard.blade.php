@extends('layouts.user')

@section('title', 'Dashboard Siswa')

@section('content')
    <div>
        <div class="container mx-auto px-6 py-12">
            <!-- Header di Tengah -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-3">
                    Selamat Datang, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-lg text-gray-600">
                    Kelola pesanan makananmu dengan mudah
                </p>
                <div class="mt-4 w-20 h-1 bg-blue-500 mx-auto rounded-full"></div>
            </div>

            <h2 class="text-2xl font-bold mb-8 text-gray-800">Pilih Kantin</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach ($canteens as $canteen)
                    <a href="{{ route('user.pilih-kantin', $canteen->id) }}"
                        class="group bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-blue-200 transition-all duration-300 overflow-hidden">

                        <!-- Icon/Avatar Area -->
                        <div class="p-6 pb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>

                            <!-- Content -->
                            <h4 class="text-xl font-semibold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors duration-300">
                                {{ $canteen->name }}
                            </h4>

                            <p class="text-gray-500 text-sm mb-4">
                                Lihat menu dan pesan makanan
                            </p>

                            <!-- Arrow Icon -->
                            <div class="flex items-center text-blue-500 text-sm font-medium">
                                <span class="mr-2">Pilih Kantin</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
