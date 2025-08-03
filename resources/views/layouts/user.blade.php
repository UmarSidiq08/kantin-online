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

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- Tailwind / Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

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

                <!-- User Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="flex items-center px-3 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg border border-red-200 hover:bg-red-100 hover:border-red-300 transition-all duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
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


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
    </script>

    @yield('script')
</body>

</html>
