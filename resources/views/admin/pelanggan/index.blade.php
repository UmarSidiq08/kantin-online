@extends('layouts.admin')
@section('title', 'Kelola Pelanggan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Pelanggan</h1>
        <p class="text-gray-600 mt-1">Semua pelanggan yang pernah memesan di kantin Anda</p>
    </div>

    {{-- Alert sukses/error --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabel pelanggan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Pelanggan</h3>
            <span class="text-sm text-gray-500">Total: {{ $pelanggan->count() }} pelanggan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="table-pelanggan">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal Daftar</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Total Order</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($pelanggan as $index => $user)
                        <tr class="hover:bg-gray-50 transition-colors" id="row-user-{{ $user->id }}">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->alamat ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-semibold text-blue-600">{{ $user->total_order }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($user->is_blocked)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                        Diblokir
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                        Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Lihat detail --}}
                                    <a href="{{ route('admin.pelanggan.show', $user->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>

                                    {{-- Toggle blokir --}}
                                    <button type="button"
                                        onclick="toggleBlock({{ $user->id }}, '{{ $user->name }}', {{ $user->is_blocked ? 'true' : 'false' }})"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors
                                            {{ $user->is_blocked
                                                ? 'text-green-700 bg-green-50 border-green-200 hover:bg-green-100'
                                                : 'text-red-700 bg-red-50 border-red-200 hover:bg-red-100' }}">
                                        @if ($user->is_blocked)
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Aktifkan
                                        @else
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636"/>
                                            </svg>
                                            Blokir
                                        @endif
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <p class="font-medium">Belum ada pelanggan</p>
                                    <p class="text-sm mt-1">Pelanggan akan muncul setelah ada yang melakukan pesanan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
