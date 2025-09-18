@extends('layouts.admin')

@section('title', 'Pengaturan Jam Operasional')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center mb-2">
            <div class="w-1 h-8 bg-blue-600 rounded-full mr-4"></div>
            <h1 class="text-3xl font-bold text-gray-900">Pengaturan Jam Operasional</h1>
        </div>
        <p class="text-gray-600 text-lg ml-5">Atur kapan kantin Anda buka dan tutup setiap harinya.</p>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-400 text-green-800 px-6 py-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Konfigurasi Jam Operasional</h2>
                    <p class="text-sm text-gray-600 mt-1">Kelola status dan jadwal operasional kantin</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.canteen.update-settings') }}" method="POST" class="p-8">
            @csrf
            @method('PUT')

            <!-- Status Kantin Section -->
            <div class="mb-10">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Status Kantin</h3>
                                <p class="text-sm text-gray-600 mt-1">Override manual untuk menutup kantin sementara</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_open" value="1" class="sr-only peer"
                                   {{ $canteen->is_open ? 'checked' : '' }}>
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600 shadow-sm"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Jadwal Harian Section -->
            <div class="space-y-6">
                <div class="flex items-center mb-6">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Jadwal Harian</h3>
                </div>

                <div class="grid gap-4">
                    @foreach($daysWithSchedule as $day)
                        <div class="border border-gray-200 rounded-xl p-6 hover:border-gray-300 transition-all duration-200 bg-gradient-to-r from-white to-gray-50">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg flex items-center justify-center mr-4 border border-blue-100">
                                        <span class="text-sm font-semibold text-blue-700">{{ substr($day['name'], 0, 2) }}</span>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $day['name'] }}</h4>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="operating_hours[{{ $day['key'] }}][is_open]" value="1"
                                           class="sr-only peer day-toggle" data-day="{{ $day['key'] }}"
                                           {{ $day['is_open'] ? 'checked' : '' }}>
                                    <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600 shadow-sm"></div>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 time-inputs-{{ $day['key'] }}" style="{{ !$day['is_open'] ? 'display: none' : '' }}">
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"></path>
                                        </svg>
                                        Jam Buka
                                    </label>
                                    <input type="time" name="operating_hours[{{ $day['key'] }}][open_time]"
                                           value="{{ $day['open_time'] }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm">
                                </div>
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                        </svg>
                                        Jam Tutup
                                    </label>
                                    <input type="time" name="operating_hours[{{ $day['key'] }}][close_time]"
                                           value="{{ $day['close_time'] }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Submit Button -->
            <div class="mt-10 flex justify-end">
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle day toggle with smooth animation
        document.querySelectorAll('.day-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const day = this.dataset.day;
                const timeInputs = document.querySelector('.time-inputs-' + day);

                if (this.checked) {
                    timeInputs.style.display = 'grid';
                    // Add fade in animation
                    timeInputs.style.opacity = '0';
                    setTimeout(() => {
                        timeInputs.style.transition = 'opacity 0.3s ease-in-out';
                        timeInputs.style.opacity = '1';
                    }, 10);
                } else {
                    timeInputs.style.transition = 'opacity 0.3s ease-in-out';
                    timeInputs.style.opacity = '0';
                    setTimeout(() => {
                        timeInputs.style.display = 'none';
                    }, 300);
                }
            });
        });

        // Add hover effects to form elements
        const inputs = document.querySelectorAll('input[type="time"], textarea');
        inputs.forEach(function(input) {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-102');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-102');
            });
        });
    });
</script>

<style>
    .scale-102 {
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
    }

    /* Custom scrollbar for textarea */
    textarea::-webkit-scrollbar {
        width: 6px;
    }

    textarea::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    textarea::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    textarea::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush
