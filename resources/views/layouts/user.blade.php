<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap & DataTables --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%2310b981;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23059669;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='32' height='32' rx='8' fill='url(%23grad)'/%3E%3Ctext x='16' y='22' text-anchor='middle' font-family='Arial, sans-serif' font-size='14' font-weight='bold' fill='white'%3EGO%3C/text%3E%3C/svg%3E">
    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- Tailwind / Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@push('styles')
    <style>
        /* sederhanakan styling bintang; gunakan Tailwind + sedikit CSS */
        .star-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0 4px;
        }

        .star-icon {
            width: 20px;
            height: 20px;
            fill: #d1d5db;
        }

        /* default gray-300 */
        .star-icon.active {
            fill: #f59e0b;
        }

        /* amber-500 */
        .rating-info {
            font-size: .9rem;
            color: #6b7280;
        }

        /* Profile Dropdown Styles */

        .profile-button:hover .profile-avatar {
            transform: scale(1.05);
        }

        .profile-avatar {
            transition: transform 0.2s ease-out;
        }

        /* Balance Display Styles */
        .balance-display {
            position: relative;
            overflow: hidden;
        }

        .balance-display::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }

        .balance-display:hover::before {
            left: 100%;
        }
    </style>
    <style>
        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes bounce-in {
            0% {
                transform: translateY(30px);
                opacity: 0;
            }

            60% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .checkmark-animation {
            animation: checkmark 0.8s ease-out;
        }

        .bounce-in {
            animation: bounce-in 0.8s ease-out 0.3s both;
        }

        .bounce-in-delay {
            animation: bounce-in 0.8s ease-out 0.6s both;
        }

        .bounce-in-delay-2 {
            animation: bounce-in 0.8s ease-out 0.9s both;
        }
    </style>
@endpush

<body class="bg-gray-50 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-white shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-2">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-2 rounded-lg">
                            <i class="fas fa-utensils text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-800">Kantin Online</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('user.dashboard') }}"
                        class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('user.orders.index') }}"
                        class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('user.orders.index') ? 'bg-green-50 text-green-700 border border-green-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <i class="fas fa-utensils mr-2"></i>
                        Menu
                    </a>

                    <a href="{{ route('user.cart.index') }}"
                        class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('user.cart.index') ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Keranjang
                    </a>
                    <a href="{{ route('user.orders.history') }}"
                        class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('user.orders.history') ? 'bg-yellow-50 text-purple-700 border border-purple-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        History
                    </a>
                </div>

                <!-- Balance & Profile Section -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Balance Display with Top Up -->
                    <div class="flex items-center space-x-2">
                        <div
                            class="balance-display bg-gradient-to-r from-green-500 to-green-600 px-3 py-2 rounded-lg text-white">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-wallet text-sm"></i>
                                <span class="text-sm font-medium">Rp
                                    {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <button id="btnTopUpNavbar"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-1">
                            <i class="fas fa-plus text-xs"></i>
                            <span>Top Up</span>
                        </button>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <!-- Profile Button -->
                        <button
                            class="profile-button flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all duration-200"
                            onclick="toggleProfileDropdown()">
                            <div
                                class="profile-avatar w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200"
                                id="profile-chevron"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-full mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                            id="profile-dropdown" hidden>
                            <!-- Profile Info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-1">
                                <a href="#"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-user mr-3 text-gray-400"></i>
                                    Profil Saya
                                </a>
                                <a href="#"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-cog mr-3 text-gray-400"></i>
                                    Pengaturan
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3 text-red-400"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button"
                        class="mobile-menu-button p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden mobile-menu hidden border-t border-gray-200 bg-white">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('user.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('user.dashboard') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fas fa-home mr-3"></i>
                    Dashboard
                </a>

                <a href="{{ route('user.orders.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('user.orders.index') ? 'bg-green-50 text-green-700 border border-green-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fas fa-utensils mr-3"></i>
                    Menu
                </a>

                <a href="{{ route('user.cart.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('user.cart.index') ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    Keranjang
                </a>

                <a href="{{ route('user.orders.history') }}"
                    class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('user.orders.history') ? 'bg-yellow-50 text-purple-700 border border-purple-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    History
                </a>

                <!-- Mobile Balance & Top Up -->
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex items-center justify-between px-4 py-2 mb-2 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-wallet text-green-600 mr-2"></i>
                            <span class="text-sm font-medium text-gray-700">Rp
                                {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
                        </div>
                        <button id="btnTopUpMobile"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-medium">
                            <i class="fas fa-plus mr-1"></i>Top Up
                        </button>
                    </div>
                </div>

                <!-- Mobile User Info -->
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex items-center px-4 py-2 mb-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white font-medium">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">Siswa</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="px-4">
                        @csrf
                        <button
                            class="flex items-center w-full px-4 py-3 bg-red-50 text-red-600 text-sm font-medium rounded-lg border border-red-200 hover:bg-red-100 transition-colors">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Top Up Modal -->
    <div id="topUpModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Top Up Saldo</h3>
                <button id="closeTopUpModal" class="text-gray-500 hover:text-gray-700">
                    <span class="text-xl">&times;</span>
                </button>
            </div>

            <div class="mb-4">
                <div class="text-sm text-gray-600 mb-2">Saldo saat ini:</div>
                <div class="text-xl font-bold text-green-600">Rp
                    {{ number_format(Auth::user()->balance, 0, ',', '.') }}</div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Top Up</label>
                <input type="number" id="topUpAmount"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    placeholder="Masukkan jumlah (min. Rp 10.000)" min="10000" max="1000000">
                <div class="text-xs text-gray-500 mt-1">Minimum Rp 10.000, Maximum Rp 1.000.000</div>
            </div>

            <div class="grid grid-cols-3 gap-2 mb-4">
                <button class="topup-quick px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium"
                    data-amount="25000">25K</button>
                <button class="topup-quick px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium"
                    data-amount="50000">50K</button>
                <button class="topup-quick px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium"
                    data-amount="100000">100K</button>
            </div>

            <button id="btnConfirmTopUp"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Top Up Sekarang
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.querySelector('.mobile-menu');
            const isHidden = mobileMenu.classList.contains('hidden');

            if (isHidden) {
                mobileMenu.classList.remove('hidden');
                this.setAttribute('aria-expanded', 'true');
            } else {
                mobileMenu.classList.add('hidden');
                this.setAttribute('aria-expanded', 'false');
            }
        });

        // Profile dropdown toggle
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            const chevron = document.getElementById('profile-chevron');

            if (dropdown.hidden) {
                dropdown.hidden = false;
                chevron.style.transform = 'rotate(180deg)';
            } else {
                dropdown.hidden = true;
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profile-dropdown');
            const profileButton = document.querySelector('.profile-button');

            if (!profileButton.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.hidden = true;
                document.getElementById('profile-chevron').style.transform = 'rotate(0deg)';
            }
        });

        // Top Up Modal functionality
        const topUpModal = document.getElementById('topUpModal');
        const btnTopUpNavbar = document.getElementById('btnTopUpNavbar');
        const btnTopUpMobile = document.getElementById('btnTopUpMobile');
        const closeTopUpModal = document.getElementById('closeTopUpModal');
        const topUpAmount = document.getElementById('topUpAmount');
        const btnConfirmTopUp = document.getElementById('btnConfirmTopUp');

        // Event listeners for both desktop and mobile top up buttons
        if (btnTopUpNavbar) {
            btnTopUpNavbar.addEventListener('click', () => {
                topUpModal.classList.remove('hidden');
            });
        }

        if (btnTopUpMobile) {
            btnTopUpMobile.addEventListener('click', () => {
                topUpModal.classList.remove('hidden');
            });
        }

        closeTopUpModal.addEventListener('click', () => {
            topUpModal.classList.add('hidden');
            topUpAmount.value = '';
        });

        // Close modal when clicking outside
        topUpModal.addEventListener('click', (e) => {
            if (e.target === topUpModal) {
                topUpModal.classList.add('hidden');
                topUpAmount.value = '';
            }
        });

        // Quick amount buttons
        document.querySelectorAll('.topup-quick').forEach(btn => {
            btn.addEventListener('click', function() {
                topUpAmount.value = this.dataset.amount;
            });
        });

        // Top Up process
        btnConfirmTopUp.addEventListener('click', function() {
            const amount = parseInt(topUpAmount.value);

            if (!amount || amount < 10000 || amount > 1000000) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Jumlah top up harus antara Rp 10.000 - Rp 1.000.000');
                } else {
                    alert('Jumlah top up harus antara Rp 10.000 - Rp 1.000.000');
                }
                return;
            }

            fetch('{{ route('user.balance.topup') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        amount: amount
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snap_token) {
                        topUpModal.classList.add('hidden');

                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                if (typeof toastr !== 'undefined') {
                                    toastr.success('Top up berhasil! Saldo Anda telah bertambah.');
                                } else {
                                    alert('Top up berhasil! Saldo Anda telah bertambah.');
                                }
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            },
                            onPending: function(result) {
                                if (typeof toastr !== 'undefined') {
                                    toastr.warning(
                                        'Top up sedang diproses. Silakan tunggu konfirmasi.');
                                } else {
                                    alert('Top up sedang diproses. Silakan tunggu konfirmasi.');
                                }
                            },
                            onError: function(result) {
                                if (typeof toastr !== 'undefined') {
                                    toastr.error('Top up gagal. Silakan coba lagi.');
                                } else {
                                    alert('Top up gagal. Silakan coba lagi.');
                                }
                            },
                            onClose: function() {
                                if (typeof toastr !== 'undefined') {
                                    toastr.info('Top up dibatalkan.');
                                } else {
                                    alert('Top up dibatalkan.');
                                }
                            }
                        });
                    } else {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(data.message || 'Gagal memproses top up.');
                        } else {
                            alert(data.message || 'Gagal memproses top up.');
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Terjadi kesalahan saat top up.');
                    } else {
                        alert('Terjadi kesalahan saat top up.');
                    }
                });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const paymentOptions = document.querySelectorAll('.payment-option');

            // Fungsi untuk update tampilan radio button
            function updateRadioDisplay() {
                paymentOptions.forEach(option => {
                    const radio = option.querySelector('input[type="radio"]');
                    const radioCustom = option.querySelector('.radio-custom');
                    const radioDot = option.querySelector('.radio-dot');
                    const paymentBadge = option.querySelector('.payment-badge');
                    const paymentText = option.querySelector('.payment-text');

                    if (radio && radio.checked) {
                        // Style untuk yang dipilih
                        if (radio.value === 'cash') {
                            option.classList.add('border-green-300', 'bg-green-50');
                            option.classList.remove('border-gray-200', 'bg-white', 'border-blue-300',
                                'bg-blue-50');
                            radioCustom.classList.add('border-green-500', 'bg-green-500');
                            radioCustom.classList.remove('border-gray-300', 'bg-white', 'border-blue-500',
                                'bg-blue-500');
                            paymentText.classList.add('text-green-700');
                            paymentText.classList.remove('text-gray-800', 'text-blue-700');
                        } else {
                            option.classList.add('border-blue-300', 'bg-blue-50');
                            option.classList.remove('border-gray-200', 'bg-white', 'border-green-300',
                                'bg-green-50');
                            radioCustom.classList.add('border-blue-500', 'bg-blue-500');
                            radioCustom.classList.remove('border-gray-300', 'bg-white', 'border-green-500',
                                'bg-green-500');
                            paymentText.classList.add('text-blue-700');
                            paymentText.classList.remove('text-gray-800', 'text-green-700');
                        }
                        radioDot.classList.add('opacity-100');
                        radioDot.classList.remove('opacity-0');
                        paymentBadge.classList.add('opacity-100');
                        paymentBadge.classList.remove('opacity-0');
                    } else if (radio) {
                        // Style untuk yang tidak dipilih
                        option.classList.add('border-gray-200', 'bg-white');
                        option.classList.remove('border-green-300', 'bg-green-50', 'border-blue-300',
                            'bg-blue-50');
                        radioCustom.classList.add('border-gray-300', 'bg-white');
                        radioCustom.classList.remove('border-green-500', 'bg-green-500', 'border-blue-500',
                            'bg-blue-500');
                        paymentText.classList.add('text-gray-800');
                        paymentText.classList.remove('text-green-700', 'text-blue-700');
                        radioDot.classList.add('opacity-0');
                        radioDot.classList.remove('opacity-100');
                        paymentBadge.classList.add('opacity-0');
                        paymentBadge.classList.remove('opacity-100');
                    }
                });
            }

            // Event listener untuk setiap option
            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        updateRadioDisplay();
                    }
                });
            });

            // Update tampilan awal
            updateRadioDisplay();
        });
    </script>

    @yield('script')
</body>
@stack('scripts')

</html>
