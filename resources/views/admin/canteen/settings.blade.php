@extends('layouts.admin')

@section('title', 'Pengaturan Jam Operasional')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan Jam Operasional</h1>
        <p class="text-gray-600 mt-1">Atur kapan kantin Anda buka dan tutup setiap harinya.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Status Kantin</h2>
        </div>

        <form action="{{ route('admin.canteen.update-settings') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Toggle Kantin Buka/Tutup -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Status Kantin</h3>
                        <p class="text-sm text-gray-600">Override manual untuk menutup kantin sementara</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_open" value="1" class="sr-only peer"
                               {{ $canteen->is_open ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>

            <!-- Jam Operasional per Hari -->
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Jadwal Harian</h3>

                @foreach($daysWithSchedule as $day)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-base font-medium text-gray-900">{{ $day['name'] }}</h4>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="operating_hours[{{ $day['key'] }}][is_open]" value="1"
                                       class="sr-only peer day-toggle" data-day="{{ $day['key'] }}"
                                       {{ $day['is_open'] ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="grid grid-cols-2 gap-4 time-inputs-{{ $day['key'] }}" style="{{ !$day['is_open'] ? 'display: none' : '' }}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Buka</label>
                                <input type="time" name="operating_hours[{{ $day['key'] }}][open_time]"
                                       value="{{ $day['open_time'] }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Tutup</label>
                                <input type="time" name="operating_hours[{{ $day['key'] }}][close_time]"
                                       value="{{ $day['close_time'] }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Catatan -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                <textarea name="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Contoh: Tutup sementara untuk renovasi">{{ $canteen->notes }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle day toggle
        document.querySelectorAll('.day-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const day = this.dataset.day;
                const timeInputs = document.querySelector('.time-inputs-' + day);

                if (this.checked) {
                    timeInputs.style.display = 'grid';
                } else {
                    timeInputs.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
