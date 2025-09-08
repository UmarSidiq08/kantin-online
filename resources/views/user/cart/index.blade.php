@extends('layouts.user')

@section('title', 'Keranjang Saya')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">🛒 Keranjang Saya</h2>
                <div class="w-16 h-1 bg-blue-500 mx-auto rounded-full"></div>
            </div>

            @if ($carts->count())
                <!-- Cart Items -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Menu</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Jumlah</th>
                                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">Harga</th>
                                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">Total</th>
                                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($carts as $cart)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900 text-sm">{{ $cart->menu->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-block w-7 h-7 bg-blue-50 text-blue-700 rounded text-sm font-medium leading-7">
                                                {{ $cart->quantity }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-medium text-gray-900 text-sm">
                                            Rp {{ number_format($cart->menu->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-green-600 text-sm">
                                            Rp {{ number_format($cart->menu->price * $cart->quantity, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button
                                                class="px-2 py-1 bg-gray-100 hover:bg-red-50 text-red-600 text-xs font-medium rounded border border-gray-200 hover:border-red-200 transition-colors btnDeleteCart"
                                                data-url="{{ route('user.cart.destroy', $cart->id) }}">
                                                🗑️ Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Checkout Section -->
                <div class="bg-white rounded-xl shadow-md p-5">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-800">Total Pesanan</h3>
                        <div class="text-xl font-bold text-green-600">
                            Rp
                            {{ number_format($carts->sum(function ($cart) {return $cart->menu->price * $cart->quantity;}),0,',','.') }}
                        </div>
                    </div>

                    <!-- Payment Method Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-800 mb-4">Metode Pembayaran:</label>
                        <div class="space-y-3">
                            <!-- Cash Payment Option -->
                            <label
                                class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 payment-option hover:border-green-300"
                                data-payment="cash">
                                <input type="radio" name="payment_method" id="payment_cash" value="cash" class="hidden"
                                    checked>
                                <div class="flex items-center w-full">
                                    <div
                                        class="radio-custom w-5 h-5 border-2 border-green-500 bg-green-500 rounded-full mr-4 flex items-center justify-center transition-all duration-200">
                                        <div
                                            class="radio-dot w-2 h-2 bg-white rounded-full opacity-100 transition-opacity duration-200">
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center">
                                            <span class="text-2xl mr-3">💰</span>
                                            <div>
                                                <div class="payment-text font-medium text-green-700 text-sm">Tunai (Cash)
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">Pembayaran langsung dengan uang
                                                    tunai</div>
                                            </div>
                                        </div>
                                        <div
                                            class="payment-badge text-green-600 font-medium text-xs opacity-100 transition-opacity duration-200 bg-green-50 px-2 py-1 rounded-full">
                                            ✓ Dipilih
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- Digital Payment Option -->
                            <label
                                class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 payment-option hover:border-blue-300"
                                data-payment="digital">
                                <input type="radio" name="payment_method" id="payment_digital" value="digital"
                                    class="hidden">
                                <div class="flex items-center w-full">
                                    <div
                                        class="radio-custom w-5 h-5 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center transition-all duration-200">
                                        <div
                                            class="radio-dot w-2 h-2 bg-white rounded-full opacity-0 transition-opacity duration-200">
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center">
                                            <span class="text-2xl mr-3">💳</span>
                                            <div>
                                                <div class="payment-text font-medium text-gray-800 text-sm">Non Cash</div>
                                                <div class="text-xs text-gray-500 mt-1">Transfer Bank, E-Wallet, Kartu
                                                    Kredit</div>
                                            </div>
                                        </div>
                                        <div
                                            class="payment-badge text-blue-600 font-medium text-xs opacity-0 transition-opacity duration-200 bg-blue-50 px-2 py-1 rounded-full">
                                            ✓ Dipilih
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- Balance Payment Option -->
                            <label
                                class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 payment-option hover:border-purple-300 {{ Auth::user()->balance <$carts->sum(function ($cart) {return $cart->menu->price * $cart->quantity;})? 'opacity-50 cursor-not-allowed': '' }}"
                                data-payment="balance">
                                <input type="radio" name="payment_method" id="payment_balance" value="balance"
                                    class="hidden"
                                    {{ Auth::user()->balance <$carts->sum(function ($cart) {return $cart->menu->price * $cart->quantity;})? 'disabled': '' }}>
                                <div class="flex items-center w-full">
                                    <div
                                        class="radio-custom w-5 h-5 border-2 border-gray-300 rounded-full mr-4 flex items-center justify-center transition-all duration-200">
                                        <div
                                            class="radio-dot w-2 h-2 bg-white rounded-full opacity-0 transition-opacity duration-200">
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center">
                                            <span class="text-2xl mr-3">🪙</span>
                                            <div>
                                                <div class="payment-text font-medium text-gray-800 text-sm">Saldo Kantin
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Saldo: Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}
                                                    @if (Auth::user()->balance <
                                                            $carts->sum(function ($cart) {
                                                                return $cart->menu->price * $cart->quantity;
                                                            }))
                                                        <span class="text-red-500 font-medium">(Tidak mencukupi)</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="payment-badge text-purple-600 font-medium text-xs opacity-0 transition-opacity duration-200 bg-purple-50 px-2 py-1 rounded-full">
                                            ✓ Dipilih
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button id="btnCheckout" data-url="{{ route('user.checkout') }}"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-5 rounded-lg border border-green-600 hover:border-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        💳 Checkout Sekarang
                    </button>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="bg-white rounded-xl shadow-md p-8 text-center">
                    <div class="mb-4">
                        <i class="fas fa-shopping-cart text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Keranjang Kosong</h3>
                    <p class="text-gray-500 mb-5 text-sm">Belum ada menu yang ditambahkan ke keranjang</p>
                    <a href="{{ route('user.orders.index') }}"
                        class="inline-block px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded border border-blue-600 hover:border-blue-700 transition-colors text-sm">
                        🍽️ Lihat Menu
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}", 'Berhasil 🚀');
            localStorage.removeItem('quantities');
        @endif
        @if (session('error'))
            toastr.error("{{ session('error') }}", 'Gagal 😢');
        @endif
        @if (session('warning'))
            toastr.warning("{{ session('warning') }}", 'Peringatan ⚠️');
        @endif
    </script>
    <script>
        // Payment option selection
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                const paymentType = this.dataset.payment;
                const radio = this.querySelector('input[type="radio"]');

                // Skip if disabled (insufficient balance)
                if (radio.disabled) return;

                // Reset all options
                document.querySelectorAll('.payment-option').forEach(opt => {
                    const optRadio = opt.querySelector('.radio-custom');
                    const optDot = opt.querySelector('.radio-dot');
                    const optBadge = opt.querySelector('.payment-badge');
                    const optText = opt.querySelector('.payment-text');

                    optRadio.classList.remove('border-green-500', 'bg-green-500', 'border-blue-500',
                        'bg-blue-500', 'border-purple-500', 'bg-purple-500');
                    optRadio.classList.add('border-gray-300');
                    optDot.classList.add('opacity-0');
                    optBadge.classList.add('opacity-0');
                    optText.classList.remove('text-green-700', 'text-blue-700', 'text-purple-700');
                    optText.classList.add('text-gray-800');
                });

                // Activate selected option
                const radioCustom = this.querySelector('.radio-custom');
                const radioDot = this.querySelector('.radio-dot');
                const badge = this.querySelector('.payment-badge');
                const text = this.querySelector('.payment-text');

                radio.checked = true;
                radioDot.classList.remove('opacity-0');
                badge.classList.remove('opacity-0');

                if (paymentType === 'cash') {
                    radioCustom.classList.remove('border-gray-300');
                    radioCustom.classList.add('border-green-500', 'bg-green-500');
                    text.classList.remove('text-gray-800');
                    text.classList.add('text-green-700');
                } else if (paymentType === 'digital') {
                    radioCustom.classList.remove('border-gray-300');
                    radioCustom.classList.add('border-blue-500', 'bg-blue-500');
                    text.classList.remove('text-gray-800');
                    text.classList.add('text-blue-700');
                } else if (paymentType === 'balance') {
                    radioCustom.classList.remove('border-gray-300');
                    radioCustom.classList.add('border-purple-500', 'bg-purple-500');
                    text.classList.remove('text-gray-800');
                    text.classList.add('text-purple-700');
                }
            });
        });

        // Checkout functionality
        document.getElementById('btnCheckout').addEventListener('click', function() {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

            if (selectedMethod === 'cash') {
                // Checkout metode tunai
                fetch('{{ route('user.checkout.cash') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_method: selectedMethod
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = "{{ route('user.payment.success') }}";
                        } else {
                            alert(data.message || 'Checkout tunai gagal.');
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Terjadi kesalahan saat checkout tunai.');
                    });

            } else if (selectedMethod === 'balance') {
                // Checkout dengan saldo
                fetch('{{ route('user.checkout.balance') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_method: selectedMethod
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message, 'Pembayaran Berhasil 🚀');
                            setTimeout(() => {
                                window.location.href = "{{ route('user.payment.success') }}";
                            }, 1500);
                        } else {
                            toastr.error(data.message || 'Checkout dengan saldo gagal.');
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        toastr.error('Terjadi kesalahan saat checkout dengan saldo.');
                    });

            } else {
                // Checkout via Midtrans Snap
                fetch('{{ route('user.checkout') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_method: selectedMethod
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.snap_token) {
                            snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    toastr.success(
                                        "Pembayaran berhasil! Pesanan Anda sedang diproses.");
                                    setTimeout(() => {
                                        window.location.href =
                                            "{{ route('user.payment.success') }}";
                                    }, 1500);
                                },
                                onPending: function(result) {
                                    // Treat pending sama seperti close - kembali ke cart tanpa action
                                    toastr.info(
                                        "Pembayaran belum selesai. Keranjang Anda masih tersimpan."
                                        );
                                    // Tidak ada redirect, tetap di halaman cart
                                },
                                onError: function(result) {
                                    toastr.error('Pembayaran gagal. Silakan coba lagi.');
                                    // Tetap di halaman cart
                                },
                                onClose: function() {
                                    toastr.info(
                                        'Pembayaran dibatalkan. Keranjang Anda masih tersimpan.'
                                        );
                                    // Tetap di halaman cart
                                }
                            });
                        } else {
                            alert(data.error || 'Checkout gagal.');
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Terjadi kesalahan saat checkout.');
                    });
            }
        });

        // Delete cart item
        $('.btnDeleteCart').on('click', function(e) {
            e.preventDefault();

            const url = $(this).data('url');

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success(response.message || 'Item berhasil dihapus 🗑️');
                    window.location.reload();
                },
                error: function() {
                    toastr.error('Gagal menghapus item ❌');
                }
            });
        });
    </script>
@endsection
