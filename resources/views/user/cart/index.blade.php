@extends('layouts.user')

@section('title', 'Keranjang Saya')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">🛒 Keranjang Saya</h2>
            <div class="w-16 h-1 bg-blue-500 mx-auto rounded-full"></div>
        </div>

        @if ($carts->count())
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            @foreach ($cartsData as $canteenId => $canteenData)
            <div class="canteen-section {{ !$loop->last ? 'border-b-2 border-gray-100' : '' }}">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 {{ $loop->first ? '' : 'border-t-2 border-white' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold">🏪</span>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg">{{ $canteenData['canteen_name'] }}</h3>
                                <p class="text-blue-100 text-sm">{{ $canteenData['count'] }} item(s)</p>
                                @if($canteenData['savings'] > 0)
                                <p class="text-yellow-200 text-xs">💰 Hemat Rp {{ number_format($canteenData['savings'], 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            @if($canteenData['savings'] > 0)
                            <div class="text-yellow-200 text-sm line-through">Rp {{ number_format($canteenData['original_total'], 0, ',', '.') }}</div>
                            @endif
                            <div class="text-white font-bold text-lg">Rp {{ number_format($canteenData['total'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

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
                            @foreach ($canteenData['items'] as $cart)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 text-sm">{{ $cart->menu->name }}</div>
                                    @if($cart->hasActiveDiscount())
                                    <div class="mt-1">
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                            🏷️ Diskon {{ $cart->active_discount->formatted_value }}
                                        </span>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block w-8 h-8 bg-blue-50 text-blue-700 rounded-full text-sm font-medium leading-8">{{ $cart->quantity }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if($cart->hasActiveDiscount())
                                    <div class="text-gray-400 text-xs line-through">Rp {{ number_format($cart->menu->price, 0, ',', '.') }}</div>
                                    <div class="font-medium text-green-600 text-sm">Rp {{ number_format($cart->discounted_price, 0, ',', '.') }}</div>
                                    @else
                                    <div class="font-medium text-gray-900 text-sm">Rp {{ number_format($cart->menu->price, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="font-semibold text-green-600 text-sm">Rp {{ number_format($cart->total_price, 0, ',', '.') }}</div>
                                    @if($cart->total_savings > 0)
                                    <div class="text-xs text-gray-500">Hemat: Rp {{ number_format($cart->total_savings, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button class="px-3 py-1.5 bg-gray-100 hover:bg-red-50 text-red-600 text-xs font-medium rounded-lg border border-gray-200 hover:border-red-200 transition-all duration-200 btnDeleteCart hover:shadow-sm" data-url="{{ url('user/keranjang/hapus/' . $cart->id) }}">
                                        🗑️ Hapus
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 p-6">
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">Subtotal {{ $canteenData['canteen_name'] }}</h4>
                            @if($canteenData['savings'] > 0)
                            <p class="text-sm text-green-600 mt-1">💰 Total Hemat: Rp {{ number_format($canteenData['savings'], 0, ',', '.') }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            @if($canteenData['savings'] > 0)
                            <div class="text-sm text-gray-400 line-through">Rp {{ number_format($canteenData['original_total'], 0, ',', '.') }}</div>
                            @endif
                            <div class="text-xl font-bold text-green-600">Rp {{ number_format($canteenData['total'], 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button class="btnShowPayment w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-[1.02]" data-canteen="{{ $canteenId }}">
                            💳 Checkout {{ $canteenData['canteen_name'] }} - Rp {{ number_format($canteenData['total'], 0, ',', '.') }}
                        </button>

                        <div class="payment-methods-dropdown hidden" id="payment-methods-{{ $canteenId }}" data-canteen="{{ $canteenId }}">
                            <div class="bg-white border-2 border-green-200 rounded-lg p-4 shadow-inner">
                                <h5 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    Pilih Metode Pembayaran
                                </h5>

                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 payment-option hover:border-green-300 hover:bg-green-50" data-payment="cash" data-canteen="{{ $canteenId }}">
                                        <input type="radio" name="payment_method_{{ $canteenId }}" value="cash" class="hidden" checked>
                                        <div class="flex items-center w-full">
                                            <div class="radio-custom w-5 h-5 border-2 border-green-500 bg-green-500 rounded-full mr-3 flex items-center justify-center">
                                                <div class="radio-dot w-2 h-2 bg-white rounded-full"></div>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-xl mr-3">💰</span>
                                                <div>
                                                    <div class="payment-text font-medium text-green-700 text-sm">Tunai (Cash)</div>
                                                    <div class="text-xs text-gray-500">Bayar langsung di kantin</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 payment-option hover:border-blue-300 hover:bg-blue-50" data-payment="digital" data-canteen="{{ $canteenId }}">
                                        <input type="radio" name="payment_method_{{ $canteenId }}" value="digital" class="hidden">
                                        <div class="flex items-center w-full">
                                            <div class="radio-custom w-5 h-5 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                                <div class="radio-dot w-2 h-2 bg-white rounded-full opacity-0"></div>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-xl mr-3">💳</span>
                                                <div>
                                                    <div class="payment-text font-medium text-gray-800 text-sm">Non Cash</div>
                                                    <div class="text-xs text-gray-500">Transfer, E-wallet, dll</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 payment-option hover:border-purple-300 hover:bg-purple-50 {{ $userBalance < $canteenData['total'] ? 'opacity-50 cursor-not-allowed' : '' }}" data-payment="balance" data-canteen="{{ $canteenId }}">
                                        <input type="radio" name="payment_method_{{ $canteenId }}" value="balance" class="hidden" {{ $userBalance < $canteenData['total'] ? 'disabled' : '' }}>
                                        <div class="flex items-center w-full">
                                            <div class="radio-custom w-5 h-5 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center">
                                                <div class="radio-dot w-2 h-2 bg-white rounded-full opacity-0"></div>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-xl mr-3">🪙</span>
                                                <div>
                                                    <div class="payment-text font-medium text-gray-800 text-sm">Saldo Kantin</div>
                                                    <div class="text-xs {{ $userBalance < $canteenData['total'] ? 'text-red-500' : 'text-gray-500' }}">
                                                        Saldo: Rp {{ number_format($userBalance, 0, ',', '.') }}
                                                        @if ($userBalance < $canteenData['total'])
                                                        (Tidak mencukupi)
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="flex gap-2 mt-4 pt-3 border-t border-gray-200">
                                    <button class="btnCancelPayment flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition-all duration-200" data-canteen="{{ $canteenId }}">❌ Batal</button>
                                    <button class="btnConfirmCheckout flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium py-2.5 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg" data-canteen="{{ $canteenId }}" data-url="{{ route('user.checkout.canteen', $canteenId) }}">✅ Konfirmasi Bayar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-1">🧾 Total Keseluruhan</h3>
                    @if($grandSavings > 0)
                    <p class="text-yellow-200 text-sm">💰 Total Penghematan: Rp {{ number_format($grandSavings, 0, ',', '.') }}</p>
                    @endif
                    <p class="text-blue-200 text-xs mt-2">Checkout dilakukan per kantin secara terpisah</p>
                </div>
                <div class="text-right">
                    @if($grandSavings > 0)
                    <div class="text-blue-200 line-through text-lg">Rp {{ number_format($grandOriginalTotal, 0, ',', '.') }}</div>
                    @endif
                    <div class="text-3xl font-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <div class="mb-6">
                <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
                </div>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-3">Keranjang Masih Kosong</h3>
            <p class="text-gray-500 mb-8 text-sm max-w-sm mx-auto">Yuk, mulai pesan makanan favorit kamu dari berbagai kantin yang tersedia!</p>
            <a href="{{ route('user.orders.index') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                🍽️ Lihat Menu Kantin
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

document.querySelectorAll('.btnShowPayment').forEach(button => {
    button.addEventListener('click', function() {
        const canteenId = this.dataset.canteen;
        const dropdown = document.getElementById(`payment-methods-${canteenId}`);
        document.querySelectorAll('.payment-methods-dropdown').forEach(d => {
            if (d !== dropdown) d.classList.add('hidden');
        });
        dropdown.classList.toggle('hidden');
        if (!dropdown.classList.contains('hidden')) {
            setTimeout(() => {
                dropdown.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }
    });
});

document.querySelectorAll('.btnCancelPayment').forEach(button => {
    button.addEventListener('click', function() {
        const canteenId = this.dataset.canteen;
        const dropdown = document.getElementById(`payment-methods-${canteenId}`);
        dropdown.classList.add('hidden');
    });
});

document.querySelectorAll('.payment-option').forEach(option => {
    option.addEventListener('click', function() {
        const paymentType = this.dataset.payment;
        const canteenId = this.dataset.canteen;
        const radio = this.querySelector('input[type="radio"]');
        if (radio.disabled) return;

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

document.querySelectorAll('.btnConfirmCheckout').forEach(button => {
    button.addEventListener('click', function() {
        const canteenId = this.dataset.canteen;
        const selectedMethod = document.querySelector(`input[name="payment_method_${canteenId}"]:checked`).value;
        this.disabled = true;
        this.innerHTML = '⏳ Processing...';

        if (selectedMethod === 'digital') {
            fetch(`/user/checkout/canteen/${canteenId}`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({payment_method: selectedMethod})
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
                            button.disabled = false;
                            button.innerHTML = '✅ Konfirmasi Bayar';
                        },
                        onError: function(result) {
                            toastr.error('Pembayaran gagal. Silakan coba lagi.');
                            button.disabled = false;
                            button.innerHTML = '✅ Konfirmasi Bayar';
                        },
                        onClose: function() {
                            toastr.info('Pembayaran dibatalkan.');
                            button.disabled = false;
                            button.innerHTML = '✅ Konfirmasi Bayar';
                        }
                    });
                } else {
                    toastr.error(data.error || 'Checkout gagal.');
                    this.disabled = false;
                    this.innerHTML = '✅ Konfirmasi Bayar';
                }
            })
            .catch(error => {
                console.error(error);
                toastr.error('Terjadi kesalahan saat checkout.');
                this.disabled = false;
                this.innerHTML = '✅ Konfirmasi Bayar';
            });
        } else if (selectedMethod === 'balance') {
            fetch(`/user/checkout/canteen/${canteenId}`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({payment_method: selectedMethod})
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
                    this.disabled = false;
                    this.innerHTML = '✅ Konfirmasi Bayar';
                }
            })
            .catch(error => {
                console.error(error);
                toastr.error('Terjadi kesalahan saat checkout dengan saldo.');
                this.disabled = false;
                this.innerHTML = '✅ Konfirmasi Bayar';
            });
        } else {
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

$('.btnDeleteCart').on('click', function(e) {
    e.preventDefault();
    const url = $(this).data('url');
    if (!confirm('Yakin ingin menghapus item ini dari keranjang?')) return;
    $.ajax({
        url: url,
        type: 'POST',
        data: {_method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content')},
        success: function(response) {
            toastr.success(response.message || 'Item berhasil dihapus 🗑️');
            setTimeout(() => { window.location.reload(); }, 1000);
        },
        error: function() {
            toastr.error('Gagal menghapus item ❌');
        }
    });
});
</script>
@endsection
