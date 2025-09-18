@extends('layouts.user')
@section('title', 'Menu Kantin')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="container mx-auto px-4 py-8 max-w-7xl">
            <div class="text-center mb-10 relative">
                <div class="absolute inset-0 -z-10">
                    <div
                        class="absolute top-8 left-1/4 w-64 h-64 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-25 animate-pulse">
                    </div>
                    <div
                        class="absolute top-12 right-1/4 w-64 h-64 bg-indigo-100 rounded-full mix-blend-multiply filter blur-xl opacity-25 animate-pulse delay-1000">
                    </div>
                </div>
                <div class="relative inline-flex items-center justify-center mb-6">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full blur-sm opacity-25 animate-pulse">
                    </div>
                    <div
                        class="relative w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full shadow-lg flex items-center justify-center transform hover:scale-105 transition-transform duration-300">
                        <span class="text-2xl">🍽️</span>
                    </div>
                </div>
                <div class="mb-4">
                    <h1 class="text-3xl md:text-4xl font-bold mb-3">
                        <span class="bg-gradient-to-r from-gray-800 via-gray-700 to-gray-800 bg-clip-text text-transparent">
                            Daftar Menu
                        </span>
                    </h1>
                    @if ($canteen)
                        <div
                            class="inline-flex items-center justify-center px-5 py-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-full border border-blue-100 shadow-sm">
                            <h2
                                class="text-lg md:text-xl font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                {{ $canteen->name }}
                            </h2>
                        </div>
                    @endif
                </div>
                <p class="text-gray-600 text-base max-w-2xl mx-auto leading-relaxed">
                    Nikmati berbagai pilihan makanan dan minuman segar yang disiapkan khusus untuk Anda
                </p>
                <div class="flex items-center justify-center mt-6">
                    <div class="w-8 h-0.5 bg-gradient-to-r from-transparent via-blue-400 to-blue-600 rounded-full"></div>
                    <div class="w-20 h-0.5 bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-600 mx-2 rounded-full">
                    </div>
                    <div class="w-8 h-0.5 bg-gradient-to-r from-purple-600 via-indigo-500 to-transparent rounded-full">
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col lg:flex-row justify-between items-center mb-8 gap-4 bg-white/50 backdrop-blur-sm rounded-2xl p-4 border border-white/20 shadow-sm">
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z">
                                </path>
                            </svg>
                            Kategori:
                        </div>
                        <form id="category-form" method="GET" action="{{ route('user.orders.index') }}">
                            <input type="hidden" name="sort" value="{{ request('sort', 'default') }}">
                            <select name="category" id="category-select"
                                onchange="document.getElementById('category-form').submit()"
                                class="pl-4 pr-8 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 cursor-pointer hover:border-gray-300 text-gray-700 font-medium min-w-[140px] text-sm">
                                @foreach ($categoryOptions as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ request('category', 'semua') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V5a2 2 0 012-2h14a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            Urutkan:
                        </div>
                        <form id="sort-form" method="GET" action="{{ route('user.orders.index') }}">
                            <input type="hidden" name="category" value="{{ request('category', 'semua') }}">
                            <select name="sort" id="sort-select" onchange="document.getElementById('sort-form').submit()"
                                class="pl-4 pr-8 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 cursor-pointer hover:border-gray-300 text-gray-700 font-medium min-w-[140px] text-sm">
                                @foreach ($sortOptions as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ request('sort', 'default') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                <form id="form-menu" action="{{ route('user.cart.add') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="group relative px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 focus:ring-4 focus:ring-blue-200 focus:outline-none"
                        id="btn-submit-menu">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 group-hover:animate-bounce" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.8 5.2M7 13l1.8-5.2m0 0h12.6M9 19a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span class="text-sm">Tambah ke Keranjang</span>
                        </span>
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($menus as $menu)
                    <div
                        class="group bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 hover:border-gray-200 hover:-translate-y-1">
                        <div class="relative overflow-hidden h-44">
                            @if ($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    alt="{{ $menu->name }}">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-3xl mb-1">🍽️</div>
                                        <span class="text-gray-500 text-xs">No Image</span>
                                    </div>
                                </div>
                            @endif
                            <div class="absolute top-2 left-2">
                                <span
                                    class="px-2.5 py-1 bg-white/90 backdrop-blur-sm text-xs font-medium text-gray-700 rounded-full shadow-sm">
                                    {{ $menu->category_display }}
                                </span>
                            </div>
                            @if ($menu->has_active_discount)
                                <div class="absolute top-2 right-2">
                                    <div class="relative">
                                        <div
                                            class="px-2.5 py-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold rounded-full shadow-md">
                                            {{ $menu->discount_percentage }}% OFF
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="p-4 space-y-3">
                            <h3
                                class="font-semibold text-gray-800 text-base leading-tight group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                                {{ $menu->name }}
                            </h3>
                            @if ($menu->description && strlen(trim($menu->description)) > 3)
                                <p class="text-gray-600 text-sm line-clamp-2 leading-relaxed">
                                    {{ Str::limit($menu->description, 80) }}
                                </p>
                            @endif

                            <div class="space-y-1">
                                @if ($menu->has_active_discount)
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-sm text-gray-500 line-through font-medium">{{ $menu->formatted_original_price }}</span>
                                            <span
                                                class="px-2 py-0.5 bg-red-100 text-red-600 text-xs font-medium rounded-md">
                                                -{{ $menu->discount_percentage }}%
                                            </span>
                                        </div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ $menu->formatted_discounted_price }}
                                        </div>
                                        <div class="text-xs text-green-600 font-medium">
                                            Hemat {{ $menu->formatted_savings }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-lg font-bold text-green-600">
                                        {{ $menu->price_display }}
                                    </div>
                                @endif
                            </div>

                            @if ($menu->total_sold > 0)
                                <div class="flex items-center text-gray-500">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                        </path>
                                    </svg>
                                    <span class="text-xs">{{ $menu->formatted_total_sold }} terjual</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between">
                                @if ($menu->total_ratings > 0)
                                    <div class="flex items-center space-x-1.5">
                                        <div class="flex">
                                            @for ($i = 1; $i <= $menu->full_stars; $i++)
                                                <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                                </svg>
                                            @endfor
                                            @if ($menu->has_half_star)
                                                <div class="relative w-3.5 h-3.5">
                                                    <svg class="absolute w-3.5 h-3.5 text-gray-300 fill-current"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                                    </svg>
                                                    <svg class="absolute w-3.5 h-3.5 text-yellow-400 fill-current"
                                                        viewBox="0 0 20 20" style="clip-path: inset(0 50% 0 0);">
                                                        <path
                                                            d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            @for ($i = 1; $i <= $menu->empty_stars; $i++)
                                                <svg class="w-3.5 h-3.5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-600">{{ $menu->formatted_average_rating }}
                                            ({{ $menu->total_ratings }})</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Belum ada ulasan</span>
                                @endif
                                <a href="{{ route('user.menus.reviews', $menu->id) }}"
                                    class="text-xs text-blue-500 hover:text-blue-700 font-medium hover:underline transition-colors">
                                    {{ $menu->total_ratings > 0 ? 'Lihat Semua' : 'Beri Ulasan' }}
                                </a>
                            </div>

                            @if ($menu->has_active_discount)
                                <div
                                    class="p-2.5 bg-gradient-to-r from-red-50 to-pink-50 border border-red-100 rounded-lg">
                                    <div class="flex items-center gap-1.5 text-red-600 mb-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-xs font-medium">
                                            Promo Terbatas{{ $menu->discount_period ? ' ' . $menu->discount_period : '' }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-center gap-3 bg-gray-50 rounded-lg p-2 mt-3">
                                <button type="button" onclick="decrementQty({{ $menu->id }})"
                                    class="w-7 h-7 flex items-center justify-center bg-white border border-gray-200 rounded-md text-red-500 hover:bg-red-50 hover:border-red-200 transition-all duration-200 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" name="quantities[{{ $menu->id }}]"
                                    id="qty-{{ $menu->id }}" value="0" min="0"
                                    class="w-14 h-7 text-center font-semibold text-sm border border-gray-200 rounded-md bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" onclick="incrementQty({{ $menu->id }})"
                                    class="w-7 h-7 flex items-center justify-center bg-white border border-gray-200 rounded-md text-green-500 hover:bg-green-50 hover:border-green-200 transition-all duration-200 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($menus->isEmpty())
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🍽️</span>
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
        function updateLocalStorage(id) {
            const input = document.getElementById(`qty-${id}`);
            const value = parseInt(input.value);
            let quantities = JSON.parse(localStorage.getItem('quantities')) || {};
            if (value > 0) {
                quantities[id] = value;
            } else {
                delete quantities[id];
            }
            localStorage.setItem('quantities', JSON.stringify(quantities));
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

        function updateCartButton() {
            const quantities = JSON.parse(localStorage.getItem('quantities')) || {};
            const totalItems = Object.values(quantities).reduce((sum, qty) => sum + parseInt(qty), 0);
            const btnSubmit = document.getElementById('btn-submit-menu');
            if (totalItems > 0) {
                btnSubmit.innerHTML =
                    `<span class="flex items-center gap-2"><svg class="w-4 h-4 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.8 5.2M7 13l1.8-5.2m0 0h12.6M9 19a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z"></path></svg><span class="text-sm">Tambah ke Keranjang (${totalItems})</span></span>`;
            } else {
                btnSubmit.innerHTML =
                    `<span class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.8 5.2M7 13l1.8-5.2m0 0h12.6M9 19a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z"></path></svg><span class="text-sm">Tambah ke Keranjang</span></span>`;
            }
        }

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

        const formMenu = document.getElementById('form-menu');
        const btnSubmit = document.getElementById('btn-submit-menu');
        formMenu.addEventListener('submit', function(e) {
            const quantities = JSON.parse(localStorage.getItem('quantities')) || {};
            if (Object.keys(quantities).length === 0) {
                e.preventDefault();
                const alertDiv = document.createElement('div');
                alertDiv.className =
                    'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-lg z-50 max-w-sm';
                alertDiv.innerHTML =
                    `<div class="flex items-center gap-3"><svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span class="font-medium">Pilih minimal 1 menu terlebih dahulu!</span></div>`;
                document.body.appendChild(alertDiv);
                setTimeout(() => {
                    alertDiv.remove();
                }, 3000);
                return;
            }
            for (let id in quantities) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `quantities[${id}]`;
                hiddenInput.value = quantities[id];
                formMenu.appendChild(hiddenInput);
            }
            btnSubmit.disabled = true;
            btnSubmit.innerHTML =
                `<span class="flex items-center gap-2"><svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg><span class="text-sm">Menambahkan...</span></span>`;
        });
    </script>
@endsection
