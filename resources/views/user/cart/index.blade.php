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
                <!-- Loop untuk setiap kantin -->
                @foreach ($carts as $canteenId => $canteenCarts)
                    @php
                        $canteenName = $canteenCarts->first()->menu->canteen->name;
                        $canteenTotal = $canteenCarts->sum(function($cart) {
                            return $cart->menu->price * $cart->quantity;
                        });
                    @endphp

                    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                        <!-- Canteen Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold">🏪</span>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-semibold text-lg">{{ $canteenName }}</h3>
                                        <p class="text-blue-100 text-sm">{{ $canteenCarts->count() }} item(s)</p>
                                    </div>
                                </div>
                                <div class="text-white font-bold text-lg">
                                    Rp {{ number_format($canteenTotal, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Cart Items Table -->
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
                                    @foreach ($canteenCarts as $cart)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900 text-sm">{{ $cart->menu->name }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-block w-7 h-7 bg-blue-50 text-blue-700 rounded text-sm font-medium leading-7">
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
                                                    data-url="{{ url('user/keranjang/hapus/' . $cart->id) }}">
                                                    🗑️ Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Checkout Section per Kantin -->
                        <div class="bg-gray-50 p-5 border-t">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-md font-medium text-gray-800">Subtotal {{ $canteenName }}</h4>
                                <div class="text-lg font-bold text-green-600">
                                    Rp {{ number_format($canteenTotal, 0, ',', '.') }}
                                </div>
                            </div>

                            <!-- Payment Method Section untuk kantin ini -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Metode Pembayaran:</label>
                                <div class="space-y-2" data-canteen="{{ $canteenId }}">
                                    <!-- Cash Payment Option -->
                                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 payment-option hover:border-green-300"
                                           data-payment="cash" data-canteen="{{ $canteenId }}">
                                        <input type="radio" name="payment_method_{{ $canteenId }}" value="cash" class="hidden" checked>
                                        <div class="flex items-center w-full">
                                            <div class="radio-custom w-4 h-4 border-2 border-green-500 bg-green-500 rounded-full mr-3 flex items-center justify-center">
                                                <div class="radio-dot w-1.5 h-1.5 bg-white rounded-full"></div>
                                            </div>
                                            <div class="flex items-center justify-between w-full">
                                                <div class="flex items-center">
                                                    <span class="text-lg mr-2">💰</span>
                                                    <div>
                                                        <div class="payment-text font-medium text-green-700 text-sm">Tunai (Cash)</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Digital Payment Option -->
                                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 payment-option hover:border-blue-300"
                                           data-payment="digital" data-canteen="{{ $canteenId }}">
                                        <input type="radio" name="payment_method_{{ $canteenId }}" value="digital" class="hidden">
                                        <div class="flex items-center w-full">
                                            <div class="radio-custom w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                                <div class="radio-dot w-1.5 h-1.5 bg-white rounded-full opacity-0"></div>
                                            </div>
                                            <div class="flex items-center justify-between w-full">
                                                <div class="flex items-center">
                                                    <span class="text-lg mr-2">💳</span>
                                                    <div>
                                                        <div class="payment-text font-medium text-gray-800 text-sm">Non Cash</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Balance Payment Option -->
                                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 payment-option hover:border-purple-300 {{ Auth::user()->balance < $canteenTotal ? 'opacity-50 cursor-not-allowed' : '' }}"
                                           data-payment="balance" data-canteen="{{ $canteenId }}">
                                        <input type="radio" name="payment_method_{{ $canteenId }}" value="balance" class="hidden" {{ Auth::user()->balance < $canteenTotal ? 'disabled' : '' }}>
                                        <div class="flex items-center w-full">
                                            <div class="radio-custom w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                                <div class="radio-dot w-1.5 h-1.5 bg-white rounded-full opacity-0"></div>
                                            </div>
                                            <div class="flex items-center justify-between w-full">
                                                <div class="flex items-center">
                                                    <span class="text-lg mr-2">🪙</span>
                                                    <div>
                                                        <div class="payment-text font-medium text-gray-800 text-sm">Saldo Kantin</div>
                                                        @if (Auth::user()->balance < $canteenTotal)
                                                            <div class="text-xs text-red-500">(Tidak mencukupi)</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <button class="btnCheckoutCanteen w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                    data-canteen="{{ $canteenId }}"
                                    data-url="{{ route('user.checkout.canteen', $canteenId) }}">
                                💳 Checkout {{ $canteenName }}
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- Total Keseluruhan -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-800">Total Keseluruhan</h3>
                        <div class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($carts->flatten()->sum(function($cart) { return $cart->menu->price * $cart->quantity; }), 0, ',', '.') }}
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Checkout dilakukan per kantin secara terpisah</p>
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
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
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
        // Payment option selection per canteen
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                const paymentType = this.dataset.payment;
                const canteenId = this.dataset.canteen;
                const radio = this.querySelector('input[type="radio"]');

                // Skip if disabled (insufficient balance)
                if (radio.disabled) return;

                // Reset options untuk kantin ini saja
                document.querySelectorAll(`.payment-option[data-canteen="${canteenId}"]`).forEach(opt => {
                    const optRadio = opt.querySelector('.radio-custom');
                    const optDot = opt.querySelector('.radio-dot');
                    const optText = opt.querySelector('.payment-text');

                    optRadio.classList.remove('border-green-500', 'bg-green-500', 'border-blue-500', 'bg-blue-500', 'border-purple-500', 'bg-purple-500');
                    optRadio.classList.add('border-gray-300');
                    optDot.classList.add('opacity-0');
                    optText.classList.remove('text-green-700', 'text-blue-700', 'text-purple-700');
                    optText.classList.add('text-gray-800');
                });

                // Activate selected option
                const radioCustom = this.querySelector('.radio-custom');
                const radioDot = this.querySelector('.radio-dot');
                const text = this.querySelector('.payment-text');

                radio.checked = true;
                radioDot.classList.remove('opacity-0');

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

        // Checkout per canteen
        document.querySelectorAll('.btnCheckoutCanteen').forEach(button => {
            button.addEventListener('click', function() {
                const canteenId = this.dataset.canteen;
                const selectedMethod = document.querySelector(`input[name="payment_method_${canteenId}"]:checked`).value;

                // Disable button untuk mencegah double click
                this.disabled = true;
                this.innerHTML = 'Processing...';

                if (selectedMethod === 'digital') {
                    // Handle Midtrans payment via AJAX
                    fetch(`/user/checkout/canteen/${canteenId}`, {
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
                                    toastr.success("Pembayaran berhasil! Pesanan sedang diproses.");
                                    setTimeout(() => {
                                        window.location.href = "{{ route('user.payment.success') }}";
                                    }, 1500);
                                },
                                onPending: function(result) {
                                    toastr.info("Pembayaran belum selesai. Silakan selesaikan pembayaran Anda.");
                                    // Re-enable button
                                    button.disabled = false;
                                    button.innerHTML = `💳 Checkout Kantin`;
                                },
                                onError: function(result) {
                                    toastr.error('Pembayaran gagal. Silakan coba lagi.');
                                    // Re-enable button
                                    button.disabled = false;
                                    button.innerHTML = `💳 Checkout Kantin`;
                                },
                                onClose: function() {
                                    toastr.info('Pembayaran dibatalkan.');
                                    // Re-enable button
                                    button.disabled = false;
                                    button.innerHTML = `💳 Checkout Kantin`;
                                }
                            });
                        } else {
                            toastr.error(data.error || 'Checkout gagal.');
                            // Re-enable button
                            this.disabled = false;
                            this.innerHTML = `💳 Checkout Kantin`;
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        toastr.error('Terjadi kesalahan saat checkout.');
                        // Re-enable button
                        this.disabled = false;
                        this.innerHTML = `💳 Checkout Kantin`;
                    });
                } else if (selectedMethod === 'balance') {
                    // Handle balance payment via AJAX
                    fetch(`/user/checkout/canteen/${canteenId}`, {
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
                            toastr.success(data.message);
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        } else {
                            toastr.error(data.message || 'Checkout dengan saldo gagal.');
                            // Re-enable button
                            this.disabled = false;
                            this.innerHTML = `💳 Checkout Kantin`;
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        toastr.error('Terjadi kesalahan saat checkout dengan saldo.');
                        // Re-enable button
                        this.disabled = false;
                        this.innerHTML = `💳 Checkout Kantin`;
                    });
                } else {
                    // Handle cash payment via form submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/user/checkout/canteen/${canteenId}`;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = 'payment_method';
                    methodInput.value = selectedMethod;
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
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
