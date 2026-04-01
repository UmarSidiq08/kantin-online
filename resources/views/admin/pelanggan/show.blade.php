@extends('layouts.admin')
@section('title', 'Detail Pelanggan')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Back button --}}
    <a href="{{ route('admin.pelanggan.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-6 transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Pelanggan
    </a>

    {{-- Info User --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    <p class="text-gray-500 text-sm mt-1">📍 {{ $user->alamat ?? 'Alamat tidak diisi' }}</p>
                    <p class="text-gray-400 text-xs mt-1">Bergabung: {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Status + tombol toggle --}}
            <div class="flex flex-col items-end gap-3">
                @if ($isBlocked)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700 border border-red-200">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                        Diblokir
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700 border border-green-200">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        Aktif
                    </span>
                @endif

                <button type="button"
                    onclick="toggleBlock({{ $user->id }}, '{{ $user->name }}', {{ $isBlocked ? 'true' : 'false' }})"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border transition-colors
                        {{ $isBlocked
                            ? 'text-green-700 bg-green-50 border-green-200 hover:bg-green-100'
                            : 'text-red-700 bg-red-50 border-red-200 hover:bg-red-100' }}">
                    @if ($isBlocked)
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Aktifkan Pengguna
                    @else
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636"/>
                        </svg>
                        Blokir Pengguna
                    @endif
                </button>
            </div>
        </div>
    </div>

    {{-- Riwayat Pesanan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Pesanan</h3>
            <span class="text-sm text-gray-500">{{ $riwayatPesanan->count() }} pesanan</span>
        </div>

        @forelse ($riwayatPesanan as $order)
            <div class="px-6 py-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <span class="text-sm font-semibold text-gray-900">#{{ $order->id }}</span>
                        <span class="text-xs text-gray-400 ml-2">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @php
                        $statusColor = match($order->status) {
                            'selesai'  => 'bg-green-100 text-green-700',
                            'diproses' => 'bg-blue-100 text-blue-700',
                            'ditolak'  => 'bg-red-100 text-red-700',
                            default    => 'bg-yellow-100 text-yellow-700',
                        };
                    @endphp
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                {{-- Item pesanan --}}
                <div class="text-sm text-gray-600 mb-2">
                    {{ $order->items->map(fn($i) => $i->menu->name . ' ×' . $i->quantity)->implode(', ') }}
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">{{ ucfirst($order->payment_method ?? '-') }}</span>
                    <span class="text-sm font-bold text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        @empty
            <div class="px-6 py-10 text-center text-gray-500">
                <p>Belum ada riwayat pesanan</p>
            </div>
        @endforelse
    </div>
</div>

<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-3"></div>
@endsection

@push('scripts')
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    function showToast(message, type = 'success') {
        const cls = type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white';
        const toast = $(`<div class="${cls} px-4 py-3 rounded-lg shadow-md w-72 flex justify-between items-center">
            <span class="text-sm font-medium">${message}</span>
            <button class="ml-4 font-bold" onclick="$(this).parent().remove()">×</button>
        </div>`);
        $('#toast-container').append(toast);
        setTimeout(() => toast.fadeOut(300, () => toast.remove()), 4000);
    }

    function toggleBlock(userId, userName, isBlocked) {
        const action = isBlocked ? 'aktifkan' : 'blokir';
        if (!confirm(`Yakin ingin ${action} ${userName}?`)) return;

        $.post(`/admin/pelanggan/${userId}/toggle-block`, function(res) {
            showToast(res.message);
            setTimeout(() => location.reload(), 1000);
        }).fail(function(xhr) {
            showToast(xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
        });
    }
</script>
@endpush
