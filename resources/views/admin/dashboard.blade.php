@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 animate-fade-in">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Selamat datang di Dashboard Admin!</h1>
                <p class="text-gray-600 mt-1">Kelola sistem Anda dengan mudah</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Stats content here -->
    </div>

@endsection
