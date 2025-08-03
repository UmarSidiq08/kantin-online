@extends('layouts.user')

@section('title', 'Menu Kantin')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="container mx-auto px-4 py-8 max-w-7xl">

            {{-- Header Section --}}
            <div class="text-center mb-12 relative">
                {{-- Background Decoration --}}
                <div class="absolute inset-0 -z-10">
                    <div
                        class="absolute top-10 left-1/4 w-72 h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse">
                    </div>
                    <div
                        class="absolute top-16 right-1/4 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse delay-1000">
                    </div>
                </div>

                {{-- Icon Container --}}
                <div class="relative inline-flex items-center justify-center mb-6">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full blur-md opacity-30 animate-pulse">
                    </div>
                    <div
                        class="relative w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full shadow-xl flex items-center justify-center transform hover:scale-110 transition-transform duration-300">
                        <span class="text-3xl">🍽️</span>
                    </div>
                </div>

                {{-- Title --}}
                <div class="mb-4">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-2">
                        <span class="bg-gradient-to-r from-gray-800 via-gray-700 to-gray-800 bg-clip-text text-transparent">
                            Daftar Menu
                        </span>
                    </h1>
                    @if (isset($canteen) && $canteen->name)
                        <div
                            class="inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-full border border-blue-100 shadow-sm">
                            <h2
                                class="text-xl md:text-2xl font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                {{ $canteen->name }}
                            </h2>
                        </div>
                    @endif
                </div>

                {{-- Description --}}
                <p class="text-gray-600 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                    Nikmati berbagai pilihan makanan dan minuman segar yang disiapkan khusus untuk Anda
                </p>

                {{-- Decorative Line --}}
                <div class="flex items-center justify-center mt-6">
                    <div class="w-10 h-1 bg-gradient-to-r from-transparent via-blue-400 to-blue-600 rounded-full shadow-md">
                    </div>
                    <div
                        class="w-24 h-1 bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-600 mx-2 rounded-full shadow-lg">
                    </div>
                    <div
                        class="w-10 h-1 bg-gradient-to-r from-purple-600 via-indigo-500 to-transparent rounded-full shadow-md">
                    </div>
                </div>
            </div>

            {{-- Filter & Cart Button Section --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                {{-- Category Filter --}}
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z">
                            </path>
                        </svg>
                        Filter:
                    </div>
                    <form id="category-form" method="GET" action="{{ route('user.orders.index') }}">
                        <select name="category" id="category-select"
                            onchange="document.getElementById('category-form').submit()"
                            class="pl-4 pr-10 py-2 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 cursor-pointer hover:border-gray-300 text-gray-700 font-medium min-w-[160px]">
                            <option value="semua"
                                {{ request('category') == 'semua' || request('category') == null ? 'selected' : '' }}>
                                🍽️ Semua Menu
                            </option>
                            <option value="makanan" {{ request('category') == 'makanan' ? 'selected' : '' }}>
                                🍛 Makanan
                            </option>
                            <option value="minuman" {{ request('category') == 'minuman' ? 'selected' : '' }}>
                                🥤 Minuman
                            </option>
                            <option value="snack" {{ request('category') == 'snack' ? 'selected' : '' }}>
                                🍿 Snack
                            </option>
                        </select>
                    </form>
                </div>

                {{-- Cart Button --}}
                <form id="form-menu" action="{{ route('user.cart.add') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="group relative px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:ring-4 focus:ring-blue-200"
                        id="btn-submit-menu">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.8 5.2M7 13l1.8-5.2m0 0h12.6M9 19a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Tambah ke Keranjang
                        </span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10">
                        </div>
                    </button>
                </form>
            </div>

            {{-- Menu Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($menus as $menu)
                    <div
                        class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-gray-200">

                        {{-- Image Container --}}
                        <div class="relative overflow-hidden">
                            @if ($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                    alt="{{ $menu->name }}">
                            @else
                                <div
                                    class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-4xl mb-2">🍽️</div>
                                        <span class="text-gray-500 text-sm">No Image</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Category Badge --}}
                            <div class="absolute top-3 left-3">
                                <span
                                    class="px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-semibold text-gray-700 rounded-full shadow-sm">
                                    {{ \App\Constant::MENU_CATEGORIES[$menu->category] ?? 'Tidak Diketahui' }}
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-4">
                            {{-- Title --}}
                            <h3
                                class="font-bold text-gray-800 text-lg mb-1 group-hover:text-blue-600 transition-colors duration-200">
                                {{ $menu->name }}
                            </h3>

                            {{-- Description --}}
                            @if ($menu->description && strlen(trim($menu->description)) > 3)
                                <p class="text-gray-600 text-sm mb-2 line-clamp-2">
                                    {{ Str::limit($menu->description, 80) }}
                                </p>
                            @endif

                            {{-- Price --}}
                            <div class="mb-3">
                                <span
                                    class="text-xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- Quantity Controls --}}
                            <div class="flex items-center justify-center gap-3 bg-gray-50 rounded-xl p-2">
                                <button type="button" onclick="decrementQty({{ $menu->id }})"
                                    class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-red-500 hover:bg-red-50 hover:border-red-200 transition-all duration-200 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                        </path>
                                    </svg>
                                </button>

                                <input type="number" name="quantities[{{ $menu->id }}]" id="qty-{{ $menu->id }}"
                                    value="0" min="0"
                                    class="w-16 h-8 text-center font-bold border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                                <button type="button" onclick="incrementQty({{ $menu->id }})"
                                    class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-green-500 hover:bg-green-50 hover:border-green-200 transition-all duration-200 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Empty State --}}
            @if ($menus->isEmpty())
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl">🍽️</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Menu</h3>
                    <p class="text-gray-600">Menu untuk kategori ini belum tersedia</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Saat increment/decrement, simpan ke localStorage
        function updateLocalStorage(id) {
            const input = document.getElementById(`qty-${id}`);
            const value = parseInt(input.value);

            let quantities = JSON.parse(localStorage.getItem('quantities')) || {};
            if (value > 0) {
                quantities[id] = value;
            } else {
                delete quantities[id]; // hapus kalau 0
            }
            localStorage.setItem('quantities', JSON.stringify(quantities));

            // Update button style based on quantities
            updateCartButton();
        }

        function incrementQty(id) {
            const qtyInput = document.getElementById(`qty-${id}`);
            qtyInput.value = parseInt(qtyInput.value) + 1;
            updateLocalStorage(id);
        }

        function decrementQty(id) {
            const qtyInput = document.getElementById(`qty-${id}`);
            if (parseInt(qtyInput.value) > 0) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
                updateLocalStorage(id);
            }
        }

        // Update cart button appearance
        function updateCartButton() {
            const quantities = JSON.parse(localStorage.getItem('quantities')) || {};
            const totalItems = Object.values(quantities).reduce((sum, qty) => sum + parseInt(qty), 0);
            const btnSubmit = document.getElementById('btn-submit-menu');

            if (totalItems > 0) {
                btnSubmit.innerHTML = `
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.8 5.2M7 13l1.8-5.2m0 0h12.6M9 19a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Tambah ke Keranjang (${totalItems})
                    </span>
                `;
                btnSubmit.classList.remove('opacity-50');
                btnSubmit.classList.add('hover:shadow-xl');
            } else {
                btnSubmit.innerHTML = `
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.8 5.2M7 13l1.8-5.2m0 0h12.6M9 19a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Tambah ke Keranjang
                    </span>
                `;
            }
        }

        // Saat halaman dimuat, isi nilai dari localStorage
        window.addEventListener('DOMContentLoaded', () => {
            let quantities = JSON.parse(localStorage.getItem('quantities')) || {};
            for (let id in quantities) {
                const input = document.getElementById(`qty-${id}`);
                if (input) {
                    input.value = quantities[id];
                }
            }
            updateCartButton();
        });

        // Validasi form sebelum submit
        const formMenu = document.getElementById('form-menu');
        const btnSubmit = document.getElementById('btn-submit-menu');

        formMenu.addEventListener('submit', function(e) {
            const quantities = JSON.parse(localStorage.getItem('quantities')) || {};
            if (Object.keys(quantities).length === 0) {
                e.preventDefault();

                // Show modern alert
                const alertDiv = document.createElement('div');
                alertDiv.className =
                    'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-lg z-50 max-w-sm';
                alertDiv.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Pilih minimal 1 menu terlebih dahulu!</span>
                    </div>
                `;
                document.body.appendChild(alertDiv);

                setTimeout(() => {
                    alertDiv.remove();
                }, 3000);

                return;
            }

            // generate input hidden untuk semua item yang disimpan
            for (let id in quantities) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `quantities[${id}]`;
                hiddenInput.value = quantities[id];
                formMenu.appendChild(hiddenInput);
            }

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Menambahkan...
                </span>
            `;
        });
    </script>
@endsection
