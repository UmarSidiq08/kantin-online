@extends('layouts.admin')
@section('title', 'Buat Pesanan Baru')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="mb-6">
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-800 mb-2 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Buat Pesanan Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Buat transaksi manual untuk pelanggan</p>
        </div>

        {{-- ======== TAMBAHAN: Tampilkan error validasi dari server ======== --}}
        @if (session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start gap-2">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- ================================================================ --}}

        <form action="{{ route('admin.orders.store-manual') }}" method="POST" id="form-order">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom kiri --}}
                <div class="lg:col-span-1 space-y-5">

                    {{-- Pilih Pelanggan --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                        <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Pelanggan
                        </h3>
                        <select name="user_id" id="select-user"
                            class="w-full px-3 py-2.5 border @error('user_id') border-red-400 @else border-gray-300 @enderror rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach ($pelanggan as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        <div id="user-info" class="mt-3 p-3 bg-blue-50 border border-blue-100 rounded-lg hidden">
                            <p class="text-xs text-gray-500 mb-1">Saldo tersedia:</p>
                            <p class="text-sm font-bold text-blue-700" id="user-balance" data-balance="0">-</p>
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                        <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Metode Pembayaran
                        </h3>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-green-400 has-[:checked]:bg-green-50">
                                <input type="radio" name="payment_method" value="cash" class="text-green-500"
                                    {{ old('payment_method', 'cash') === 'cash' ? 'checked' : '' }}>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">💵 Cash</p>
                                    <p class="text-xs text-gray-500">Bayar tunai saat ambil pesanan</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="balance" class="text-blue-500"
                                    {{ old('payment_method') === 'balance' ? 'checked' : '' }}>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">💳 Saldo Digital</p>
                                    <p class="text-xs text-gray-500">Potong dari saldo pelanggan</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Status Pesanan --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                        <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status Pesanan
                        </h3>
                        <select name="status" id="select-status"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('status', 'diproses') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        <div id="stok-warning" class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                            <p class="text-xs text-yellow-700 font-medium">⚠️ Status <strong>Diproses</strong> atau
                                <strong>Selesai</strong> akan mengurangi stok menu.</p>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl p-5 text-white shadow-lg">
                        <p class="text-sm text-blue-200 mb-1">Total Pesanan</p>
                        <p class="text-3xl font-bold" id="total-display">Rp 0</p>
                        <p class="text-xs text-blue-200 mt-2" id="item-count-display">0 item dipilih</p>
                    </div>
                </div>

                {{-- Kolom kanan: Pilih Menu --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border @error('items') border-red-300 @else border-gray-200 @enderror shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Pilih Menu
                            </h3>
                            <span class="text-xs text-gray-400">{{ $menus->count() }} menu tersedia</span>
                        </div>

                        @error('items')
                            <div class="mx-4 mt-3 p-2 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-red-600 text-xs font-medium">{{ $message }}</p>
                            </div>
                        @enderror

                        <div class="p-4 space-y-2 max-h-[600px] overflow-y-auto">
                            @forelse ($menus as $menu)
                                @php
                                    $harga = $menu->getDiscountedPrice();
                                    $hasDiskon = $menu->hasActiveDiscount();
                                @endphp
                                <div class="flex items-center gap-4 p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition"
                                    data-menu-id="{{ $menu->id }}">
                                    <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        @if ($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}"
                                                class="w-full h-full object-cover" alt="{{ $menu->name }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $menu->name }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            @if ($hasDiskon)
                                                <span class="text-xs text-gray-400 line-through">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                                <span class="text-sm font-bold text-green-600">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-sm font-bold text-green-600">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-400 mt-0.5">Stok: {{ $menu->stok }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <button type="button" onclick="changeQty({{ $menu->id }}, -1)"
                                            class="w-7 h-7 flex items-center justify-center bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 rounded-md transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <input type="number"
                                            id="qty-{{ $menu->id }}"
                                            name="items[{{ $loop->index }}][quantity]"
                                            value="0" min="0" max="{{ $menu->stok }}"
                                            class="w-12 h-7 text-center text-sm font-semibold border border-gray-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none"
                                            onchange="recalculate()"
                                            data-menu-id="{{ $menu->id }}">
                                        <input type="hidden" name="items[{{ $loop->index }}][menu_id]" value="{{ $menu->id }}">
                                        <button type="button" onclick="changeQty({{ $menu->id }}, 1)"
                                            class="w-7 h-7 flex items-center justify-center bg-gray-100 hover:bg-green-100 text-gray-600 hover:text-green-600 rounded-md transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center text-gray-400">
                                    <p class="font-medium">Tidak ada menu tersedia</p>
                                    <p class="text-sm mt-1">Semua menu habis stoknya</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('admin.orders.index') }}"
                            class="flex-1 text-center px-5 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="button" onclick="submitOrder()" id="btn-submit"
                            class="flex-1 px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const menuData = {
            @foreach ($menus as $menu)
                {{ $menu->id }}: {
                    price: {{ $menu->getDiscountedPrice() }},
                    stok: {{ $menu->stok }},
                    name: "{{ addslashes($menu->name) }}"
                },
            @endforeach
        };

        function changeQty(menuId, delta) {
            const input = document.getElementById(`qty-${menuId}`);
            const current = parseInt(input.value) || 0;
            const max = menuData[menuId].stok;
            const newVal = Math.max(0, Math.min(max, current + delta));
            input.value = newVal;
            recalculate();
        }

        function recalculate() {
            let total = 0;
            let itemCount = 0;

            document.querySelectorAll('[data-menu-id]').forEach(row => {
                const menuId = row.dataset.menuId;
                if (!menuId || !menuData[menuId]) return;
                const input = document.getElementById(`qty-${menuId}`);
                if (!input) return;
                const qty = parseInt(input.value) || 0;
                if (qty > 0) {
                    total += menuData[menuId].price * qty;
                    itemCount += qty;
                    row.classList.add('ring-2', 'ring-blue-200', 'bg-blue-50');
                } else {
                    row.classList.remove('ring-2', 'ring-blue-200', 'bg-blue-50');
                }
            });

            document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('item-count-display').textContent = itemCount + ' item dipilih';

            const method = document.querySelector('input[name="payment_method"]:checked')?.value;
            const balanceRaw = parseFloat(document.getElementById('user-balance').dataset.balance || 0);
            const btn = document.getElementById('btn-submit');

            if (method === 'balance' && balanceRaw > 0 && total > balanceRaw) {
                btn.disabled = true;
                btn.textContent = 'Saldo Tidak Cukup';
            } else {
                btn.disabled = false;
                btn.textContent = 'Buat Pesanan';
            }
        }

        // ======== FIX: Validasi client-side sebelum submit ========
        function submitOrder() {
            const userId = document.getElementById('select-user').value;
            if (!userId) {
                alert('Silakan pilih pelanggan terlebih dahulu.');
                return;
            }

            // Cek apakah ada minimal 1 item qty > 0
            let hasItem = false;
            document.querySelectorAll('[data-menu-id]').forEach(row => {
                const menuId = row.dataset.menuId;
                if (!menuId || !menuData[menuId]) return;
                const input = document.getElementById(`qty-${menuId}`);
                if (input && parseInt(input.value) > 0) hasItem = true;
            });

            if (!hasItem) {
                alert('Pilih minimal 1 menu dengan jumlah lebih dari 0.');
                return;
            }

            document.getElementById('btn-submit').disabled = true;
            document.getElementById('btn-submit').textContent = 'Memproses...';
            document.getElementById('form-order').submit();
        }
        // ===========================================================

        document.getElementById('select-user').addEventListener('change', function() {
            const userId = this.value;
            if (!userId) {
                document.getElementById('user-info').classList.add('hidden');
                return;
            }
            fetch(`/admin/pelanggan/${userId}/info`)
                .then(res => res.json())
                .then(data => {
                    const balanceEl = document.getElementById('user-balance');
                    balanceEl.textContent = data.formatted_balance;
                    balanceEl.dataset.balance = data.balance;
                    document.getElementById('user-info').classList.remove('hidden');
                    recalculate();
                });
        });

        document.getElementById('select-status').addEventListener('change', function() {
            const warning = document.getElementById('stok-warning');
            if (['diproses', 'selesai'].includes(this.value)) {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        });

        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', recalculate);
        });

        document.getElementById('select-status').dispatchEvent(new Event('change'));
    </script>
@endpush
