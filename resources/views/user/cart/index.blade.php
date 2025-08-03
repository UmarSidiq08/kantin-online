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
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Total Pesanan</h3>
                        <div class="text-xl font-bold text-green-600">
                            Rp
                            {{ number_format($carts->sum(function ($cart) {return $cart->menu->price * $cart->quantity;}),0,',','.') }}
                        </div>
                    </div>

                    <button id="btnCheckout" data-url="{{ route('user.checkout') }}"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-5 rounded border border-green-600 hover:border-green-700 transition-colors">
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
        document.getElementById('btnCheckout').addEventListener('click', function() {
            fetch('{{ route('user.checkout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snap_token) {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = "{{ route('user.payment.success') }}";
                            },
                            onPending: function(result) {
                                window.location.href = "{{ route('user.payment.success') }}";
                            },

                            onError: function(result) {
                                alert('Pembayaran gagal.');
                            },
                            onClose: function() {
                                alert('Kamu menutup pembayaran sebelum selesai.');
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
        });

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
