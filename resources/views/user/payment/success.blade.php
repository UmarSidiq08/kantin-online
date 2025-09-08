@extends('layouts.user')

@section('title', 'Pembayaran Berhasil')



@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Success Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-blue-500"></div>
                <div class="absolute -top-2 -right-2 w-20 h-20 bg-green-100 rounded-full opacity-20"></div>
                <div class="absolute -bottom-3 -left-3 w-16 h-16 bg-blue-100 rounded-full opacity-20"></div>

                <!-- Success Icon -->
                <div class="relative mb-6">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-full mx-auto flex items-center justify-center checkmark-animation">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="space-y-4 bounce-in">
                    <h1 class="text-2xl font-bold text-gray-800">Pembayaran Berhasil!</h1>
                    <p class="text-gray-600 leading-relaxed">
                        Terima kasih! Pesanan Anda telah berhasil diproses dan sedang dalam tahap persiapan.
                    </p>
                </div>

                <!-- Order Status Info -->
                <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-lg p-4 my-6 bounce-in-delay">
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-700">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>

                    </div>
                    <p class="text-xs text-gray-500 mt-1">Anda akan mendapat notifikasi saat pesanan siap</p>
                </div>
                <!-- Rating Section -->
                <!-- Rating Section -->
                @if (isset($order) && $order && $order->items->count() > 0)
                    <div class="bg-gray-50 rounded-lg p-4 my-6 bounce-in-delay">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 text-center">Bagaimana menu yang Anda pesan?
                        </h3>
                        <div class="space-y-2">
                            @foreach ($order->items as $item)
                                <div class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm">
                                    <div class="flex items-center space-x-3">
                                        @if ($item->menu && $item->menu->image)
                                            <img src="{{ asset('storage/' . $item->menu->image) }}"
                                                class="w-10 h-10 rounded-lg object-cover" alt="{{ $item->menu->name }}">
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <span class="text-lg">🍽️</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-800">
                                                {{ $item->menu->name ?? 'Menu tidak ditemukan' }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->quantity }}x</p>
                                        </div>
                                    </div>

                                    @if ($item->menu)
                                        @php
                                            $hasRated = auth()
                                                ->user()
                                                ->ratings()
                                                ->where('menu_id', $item->menu_id)
                                                ->exists();
                                        @endphp

                                        @if ($hasRated)
                                            <span class="text-xs text-green-600 font-medium">✓ Sudah dinilai</span>
                                        @else
                                            <button
                                                onclick="openRatingModal({{ $item->menu_id }}, '{{ $item->menu->name }}', {{ $order->id }})"
                                                class="text-xs bg-blue-500 text-white px-3 py-1 rounded-full hover:bg-blue-600 transition-colors">
                                                Beri Rating
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-3 bounce-in-delay-2">
                    <a href="{{ route('user.dashboard') }}"
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl inline-block">
                        Kembali ke Beranda
                    </a>

                    <a href="{{ route('user.orders.history') ?? '#' }}"
                        class="w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg border-2 border-gray-200 hover:border-gray-300 transition-all duration-200 inline-block">
                        Lihat Status Pesanan
                    </a>
                </div>

                <!-- Additional Info -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Est. 15-30 menit</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Ambil di kantin</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thank You Message -->
            <div class="text-center mt-6 bounce-in-delay-2">
                <p class="text-gray-600 text-sm">
                    Ada pertanyaan?
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Hubungi kami</a>
                </p>
            </div>
        </div>
    </div>
    <!-- Rating Modal -->
    <div id="ratingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Beri Rating</h3>
            <div id="menuName" class="text-gray-600 mb-4 text-center font-medium"></div>

            <!-- Star Rating -->
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Rating:</p>
                <div class="flex justify-center space-x-1" id="starRating">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-8 h-8 text-gray-300 cursor-pointer hover:text-yellow-400 star transition-colors"
                            data-rating="{{ $i }}" viewBox="0 0 20 20">
                            <path fill="currentColor"
                                d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                        </svg>
                    @endfor
                </div>
            </div>

            <!-- Review Text -->
            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-2">Ulasan (opsional):</p>
                <textarea id="reviewText" placeholder="Ceritakan pengalaman Anda..."
                    class="w-full p-3 border border-gray-200 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    rows="3" maxlength="500"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-3">
                <button onclick="closeRatingModal()"
                    class="flex-1 py-2 px-4 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <button onclick="submitRating()"
                    class="flex-1 py-2 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Kirim Rating
                </button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let currentMenuId = null;
        let currentOrderId = null;
        let selectedRating = 0;

        function openRatingModal(menuId, menuName, orderId) {
            currentMenuId = menuId;
            currentOrderId = orderId;
            document.getElementById('menuName').textContent = menuName;
            document.getElementById('ratingModal').classList.remove('hidden');
        }

        function closeRatingModal() {
            document.getElementById('ratingModal').classList.add('hidden');
            selectedRating = 0;
            document.getElementById('reviewText').value = '';
            updateStars();
        }

        // Star rating logic
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.dataset.rating);
                updateStars();
            });

            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                document.querySelectorAll('.star').forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('text-yellow-400');
                        s.classList.remove('text-gray-300');
                    } else {
                        s.classList.add('text-gray-300');
                        s.classList.remove('text-yellow-400');
                    }
                });
            });
        });

        document.getElementById('starRating').addEventListener('mouseleave', function() {
            updateStars();
        });

        function updateStars() {
            document.querySelectorAll('.star').forEach((star, index) => {
                if (index < selectedRating) {
                    star.classList.add('text-yellow-400');
                    star.classList.remove('text-gray-300');
                } else {
                    star.classList.add('text-gray-300');
                    star.classList.remove('text-yellow-400');
                }
            });
        }

        function submitRating() {
            if (selectedRating === 0) {
                alert('Silakan pilih rating terlebih dahulu');
                return;
            }

            fetch('/ratings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        menu_id: currentMenuId,
                        order_id: currentOrderId,
                        rating: selectedRating,
                        review_text: document.getElementById('reviewText').value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert('Rating berhasil disimpan!');
                        closeRatingModal();
                        // Refresh atau hide button
                        location.reload();
                    } else {
                        alert(data.error || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengirim rating');
                });
        }
    </script>
@endsection
