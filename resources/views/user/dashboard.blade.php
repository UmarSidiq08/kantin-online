{{-- resources/views/user/dashboard.blade.php --}}
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
            <h2 class="text-2xl font-bold mb-4">Pilih Kantin</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                @foreach ($canteens as $canteen)
                    <a href="{{ route('user.pilih-kantin', $canteen->id) }}"
                        class="bg-white p-6 rounded-xl shadow hover:shadow-md transition duration-300 text-center border group">
                        {{-- <img src="https://via.placeholder.com/100x100?text=Kantin"
                            class="w-24 h-24 mx-auto rounded-full mb-4"> --}}
                        <h4 class="text-lg font-semibold text-gray-700 group-hover:text-blue-600">
                            {{ $canteen->name }}
                        </h4>
                    </a>
                @endforeach
            </div>


            <!-- Main Cards -->
            {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">

                <!-- Card: Pesan Makanan -->
                <a href="{{ route('user.orders.index') }}" class="group">
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-blue-200">
                        <div class="text-center">
                            <div
                                class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-utensils text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Pesan Makanan</h3>
                            <p class="text-gray-600 mb-4 leading-relaxed">
                                Jelajahi menu lezat dan pesan makanan favoritmu 🍽️
                            </p>
                            <span class="inline-flex items-center text-blue-600 font-medium group-hover:text-blue-700">
                                Mulai Pesan
                                <i class="fas fa-arrow-right ml-2 text-sm"></i>
                            </span>
                        </div>
                    </div>
                </a>

                <!-- Card: Keranjang -->
                <a href="{{ route('user.cart.index') }}" class="group">
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-green-200">
                        <div class="text-center">
                            <div
                                class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                                <i class="fas fa-shopping-cart text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Keranjang</h3>
                            <p class="text-gray-600 mb-4 leading-relaxed">
                                Lihat dan kelola semua pesanan yang sudah dipilih 🛒
                            </p>
                            <span class="inline-flex items-center text-green-600 font-medium group-hover:text-green-700">
                                Lihat Keranjang
                                <i class="fas fa-arrow-right ml-2 text-sm"></i>
                            </span>
                        </div>
                    </div>
                </a>

                <!-- Card: Riwayat Pesanan -->
                <a href="{{ route('user.orders.history') }}" class="group">
                    <div
                        class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-purple-200">
                        <div class="text-center">
                            <div
                                class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                                <i class="fas fa-history text-purple-600 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Riwayat Pesanan</h3>
                            <p class="text-gray-600 mb-4 leading-relaxed">
                                Pantau status dan lihat histori transaksimu 📄
                            </p>
                            <span class="inline-flex items-center text-purple-600 font-medium group-hover:text-purple-700">
                                Lihat Riwayat
                                <i class="fas fa-arrow-right ml-2 text-sm"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div> --}}

            <!-- Info Section -->

        </div>
    </div>
@endsection
