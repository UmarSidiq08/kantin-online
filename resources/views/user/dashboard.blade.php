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
                        <h4 class="text-lg font-semibold text-gray-700 group-hover:text-blue-600">
                            {{ $canteen->name }}
                        </h4>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
