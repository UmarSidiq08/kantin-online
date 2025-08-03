@extends('layouts.user')

@section('title', 'Pembayaran Berhasil')

@push('styles')
<style>
    @keyframes checkmark {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes bounce-in {
        0% {
            transform: translateY(30px);
            opacity: 0;
        }
        60% {
            transform: translateY(-10px);
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .checkmark-animation {
        animation: checkmark 0.8s ease-out;
    }

    .bounce-in {
        animation: bounce-in 0.8s ease-out 0.3s both;
    }

    .bounce-in-delay {
        animation: bounce-in 0.8s ease-out 0.6s both;
    }

    .bounce-in-delay-2 {
        animation: bounce-in 0.8s ease-out 0.9s both;
    }
</style>
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Success Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-blue-500"></div>
                <div class="absolute -top-2 -right-2 w-20 h-20 bg-green-100 rounded-full opacity-20"></div>
                <div class="absolute -bottom-3 -left-3 w-16 h-16 bg-blue-100 rounded-full opacity-20"></div>

                <!-- Success Icon -->
                <div class="relative mb-6">
                    <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-full mx-auto flex items-center justify-center checkmark-animation">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="space-y-4 bounce-in">
                    <h1 class="text-2xl font-bold text-gray-800">Pembayaran Berhasil!</h1>
                    <p class="text-gray-600 leading-relaxed">
                        Terima kasih! Pesanan Anda telah berhasil diproses dan sedang dalam tahap persiapan.
                    </p>
                </div>

                <!-- Order Status Info -->
                <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-lg p-4 my-6 bounce-in-delay">
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-700">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                        <span class="font-medium">Status: Sedang Diproses</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Anda akan mendapat notifikasi saat pesanan siap</p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3 bounce-in-delay-2">
                    <a href="{{ route('user.dashboard') }}"
                       class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl inline-block">
                        Kembali ke Beranda
                    </a>

                    <a href="{{ route('user.orders.history') ?? '#' }}"
                       class="w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg border-2 border-gray-200 hover:border-gray-300 transition-all duration-200 inline-block">
                        Lihat Status Pesanan
                    </a>
                </div>

                <!-- Additional Info -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Est. 15-30 menit</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Ambil di kantin</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thank You Message -->
            <div class="text-center mt-6 bounce-in-delay-2">
                <p class="text-gray-600 text-sm">
                    Ada pertanyaan?
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Hubungi kami</a>
                </p>
            </div>
        </div>
    </div>
@endsection
