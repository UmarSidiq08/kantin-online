<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%2310b981;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23059669;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='32' height='32' rx='8' fill='url(%23grad)'/%3E%3Ctext x='16' y='22' text-anchor='middle' font-family='Arial, sans-serif' font-size='14' font-weight='bold' fill='white'%3EGO%3C/text%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'bounce-subtle': 'bounceSubtle 0.6s ease-in-out'
                    },
                    boxShadow: {
                        'green': '0 4px 14px 0 rgba(16, 185, 129, 0.25)',
                        'green-lg': '0 10px 25px -3px rgba(16, 185, 129, 0.35)'
                    }
                }
            }
        }
    </script>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    @endpush
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-gradient-to-br from-emerald-50 to-green-50 text-gray-900 min-h-screen">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="flex min-h-screen">
        <div class="w-64 bg-gradient-to-b from-emerald-800 via-emerald-900 to-green-900 text-white fixed h-full overflow-y-auto z-50 custom-scrollbar transform transition-transform duration-300 md:translate-x-0 -translate-x-full"
            id="sidebar">
            <div class="p-6 border-b border-emerald-700/50 bg-emerald-800/50 backdrop-blur-sm">
                <div class="flex items-center justify-center gap-3">
                    <div
                        class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-green-600 shadow-lg">
                        <span class="text-xl font-bold text-white font-poppins tracking-wider">GO</span>
                    </div>
                    <h2
                        class="text-xl font-bold bg-gradient-to-r from-emerald-300 to-green-300 bg-clip-text text-transparent">
                        Admin Kantin</h2>
                </div>
            </div>
            <nav class="mt-6">
                <ul class="space-y-2 px-4">
                    <li class="animate-fade-in">
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center px-4 py-3 text-emerald-100 hover:bg-emerald-700/60 hover:text-white hover:shadow-green rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'border-l-4 border-emerald-400 bg-emerald-700/60 text-white shadow-green' : '' }}">
                            <div
                                class="w-5 h-5 mr-3 p-1 bg-emerald-600 rounded-lg group-hover:bg-emerald-500 transition-colors">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li class="animate-fade-in" style="animation-delay: 0.1s">
                        <a href="{{ route('admin.menu.index') }}"
                            class="flex items-center px-4 py-3 text-emerald-100 hover:bg-emerald-700/60 hover:text-white hover:shadow-green rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.menu*') ? 'border-l-4 border-emerald-400 bg-emerald-700/60 text-white shadow-green' : '' }}">
                            <div
                                class="w-5 h-5 mr-3 p-1 bg-emerald-600 rounded-lg group-hover:bg-emerald-500 transition-colors">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Menu Kantin</span>
                        </a>
                    </li>
                    <li class="animate-fade-in" style="animation-delay: 0.2s">
                        <a href="{{ route('admin.orders.index') }}"
                            class="flex items-center px-4 py-3 text-emerald-100 hover:bg-emerald-700/60 hover:text-white hover:shadow-green rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.orders*') ? 'border-l-4 border-emerald-400 bg-emerald-700/60 text-white shadow-green' : '' }}">
                            <div
                                class="w-5 h-5 mr-3 p-1 bg-emerald-600 rounded-lg group-hover:bg-emerald-500 transition-colors">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 2a1 1 0 100 2h4a1 1 0 100-2H7z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Daftar Pesanan</span>
                        </a>
                    </li>
                    <li class="animate-fade-in" style="animation-delay: 0.3s">
                        <a href="{{ route('admin.logs') }}"
                            class="flex items-center px-4 py-3 text-emerald-100 hover:bg-emerald-700/60 hover:text-white hover:shadow-green rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.logs*') ? 'border-l-4 border-emerald-400 bg-emerald-700/60 text-white shadow-green' : '' }}">
                            <div
                                class="w-5 h-5 mr-3 p-1 bg-emerald-600 rounded-lg group-hover:bg-emerald-500 transition-colors">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Riwayat Pesanan</span>
                        </a>
                    </li>

                    <li class="animate-fade-in" style="animation-delay: 0.4s">
                        <a href="{{ route('admin.laporan.index') }}"
                            class="flex items-center px-4 py-3 text-emerald-100 hover:bg-emerald-700/60 hover:text-white hover:shadow-green rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.laporan.index*') ? 'border-l-4 border-emerald-400 bg-emerald-700/60 text-white shadow-green' : '' }}">
                            <div
                                class="w-5 h-5 mr-3 p-1 bg-emerald-600 rounded-lg group-hover:bg-emerald-500 transition-colors">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-medium">Laporan Penjualan</span>
                        </a>
                    </li>
                    <li class="animate-fade-in" style="animation-delay: 0.4s">
                        <a href="{{ route('admin.pelanggan.index') }}"
                            class="flex items-center px-4 py-3 text-emerald-100 hover:bg-emerald-700/60 hover:text-white hover:shadow-green rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.pelanggan*') ? 'border-l-4 border-emerald-400 bg-emerald-700/60 text-white shadow-green' : '' }}">
                            <div
                                class="w-5 h-5 mr-3 p-1 bg-emerald-600 rounded-lg group-hover:bg-emerald-500 transition-colors">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-medium">Kelola Pelanggan</span>
                        </a>
                    </li>
                    <li class="animate-fade-in group relative" style="animation-delay: 0.5s">
                        <button type="button"
                            class="flex items-center w-full px-4 py-3 text-emerald-100 hover:bg-emerald-700/60 hover:text-white hover:shadow-green rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.canteen.settings*', 'admin.discount*') ? 'border-l-4 border-emerald-400 bg-emerald-700/60 text-white shadow-green' : '' }}"
                            onclick="toggleDropdown('settingsDropdown')">
                            <div
                                class="w-5 h-5 mr-3 p-1 bg-emerald-600 rounded-lg group-hover:bg-emerald-500 transition-colors">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-medium flex-1 text-left">Pengaturan</span>
                            <svg class="w-4 h-4 ml-auto transition-transform group-[.open]:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul id="settingsDropdown"
                            class="hidden group-[.open]:block absolute left-0 right-0 mt-2 bg-emerald-900/95 rounded-xl shadow-green z-50 overflow-hidden">
                            <li>
                                <a href="{{ route('admin.discount.index') }}"
                                    class="block px-8 py-3 text-emerald-100 hover:bg-emerald-700/80 hover:text-white transition-all duration-150 {{ request()->routeIs('admin.discount*') ? 'bg-emerald-700/80 text-white' : '' }}">Diskon</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.canteen.settings') }}"
                                    class="block px-8 py-3 text-emerald-100 hover:bg-emerald-700/80 hover:text-white transition-all duration-150 {{ request()->routeIs('admin.canteen.settings*') ? 'bg-emerald-700/80 text-white' : '' }}">Jam
                                    Operasional</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div class="absolute bottom-6 left-4 right-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-3 text-emerald-100 hover:bg-red-600 hover:text-white hover:shadow-lg rounded-xl transition-all duration-200 group animate-bounce-subtle">
                        <div class="w-5 h-5 mr-3 p-1 bg-red-500 rounded-lg group-hover:bg-red-400 transition-colors">
                            <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>
        <div class="flex-1 md:ml-64 transition-all duration-300">
            @hasSection('navbar')
                @yield('navbar')
            @else
                <nav
                    class="bg-white/80 backdrop-blur-md shadow-sm p-4 text-gray-800 border-b border-emerald-200/50 sticky top-0 z-30">
                    <div class="flex justify-between items-center">
                        <div class="md:hidden">
                            <button id="mobileMenuBtn"
                                class="bg-emerald-600 text-white p-2 rounded-lg shadow-green hover:bg-emerald-700 transition-colors">
                                <svg id="menuIcon" class="w-6 h-6" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="hidden md:block">
                            <x-page-header />
                        </div>
                        <div class="md:hidden flex-1 text-center ml-4">
                            <h1 class="text-lg font-semibold text-emerald-700">Admin Panel</h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-emerald-400 to-green-500 rounded-full flex items-center justify-center shadow-green">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="hidden sm:block">
                                    <span class="text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                                    <p class="text-xs text-emerald-600 font-medium">Administrator</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            @endif
            <div class="p-4 md:p-6">
                @yield('modal')
                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        function toggleDropdown(id) {
            const btn = event.currentTarget;
            const dropdown = document.getElementById(id);
            const parentLi = btn.closest('li');
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                parentLi.classList.add('open');
            } else {
                dropdown.classList.add('hidden');
                parentLi.classList.remove('open');
            }
        }
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('settingsDropdown');
            const btn = dropdown?.previousElementSibling;
            if (dropdown && !dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.classList.add('hidden');
                dropdown.closest('li').classList.remove('open');
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const menuIcon = document.getElementById('menuIcon');
            let sidebarOpen = false;

            function toggleSidebar() {
                sidebarOpen = !sidebarOpen;
                if (sidebarOpen) {
                    sidebar.classList.remove('sidebar-closed', '-translate-x-full');
                    sidebar.classList.add('sidebar-open', 'translate-x-0');
                    sidebarOverlay.classList.add('active');
                    menuIcon.classList.add('menu-icon-open');
                    menuIcon.innerHTML =
                        `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>`;
                    document.body.style.overflow = 'hidden';
                } else {
                    closeSidebar();
                }
            }

            function closeSidebar() {
                sidebarOpen = false;
                sidebar.classList.remove('sidebar-open', 'translate-x-0');
                sidebar.classList.add('sidebar-closed', '-translate-x-full');
                sidebarOverlay.classList.remove('active');
                menuIcon.classList.remove('menu-icon-open');
                menuIcon.innerHTML =
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>`;
                document.body.style.overflow = 'auto';
            }
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }
            document.addEventListener('click', function(e) {
                if (sidebarOpen && !sidebar.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                    closeSidebar();
                }
            });
            const navLinks = sidebar.querySelectorAll('nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 768) {
                        setTimeout(closeSidebar, 150);
                    }
                });
            });
            const currentPath = window.location.pathname;
            navLinks.forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (currentPath.startsWith(linkPath) && linkPath !== '/') {
                    link.classList.add('border-l-4', 'border-emerald-400', 'bg-emerald-700/60',
                        'text-white', 'shadow-green');
                    link.classList.remove('text-emerald-100');
                }
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    closeSidebar();
                    document.body.style.overflow = 'auto';
                }
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebarOpen) {
                    closeSidebar();
                }
            });
        });
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>
    @yield('script')
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", 'Berhasil 🚀', {
                iconClass: 'toast-success-green',
                backgroundColor: '#10b981'
            });
        </script>
    @endif
    @stack('scripts')
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes bounceSubtle {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-4px);
            }

            60% {
                transform: translateY(-2px);
            }
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar-overlay.active {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
        }

        * {
            transition: all 0.2s ease-in-out;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        .container {
            @apply !max-w-none !mx-0 !px-0;
        }

        .dataTables_wrapper {
            font-family: inherit;
            @apply px-6 pb-6;
        }

        .dataTables_filter input {
            @apply border border-emerald-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500;
        }

        .dataTables_length select {
            @apply border border-emerald-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500;
        }

        .dataTables_info {
            @apply text-sm text-gray-600 py-4;
        }

        .dataTables_paginate .paginate_button {
            @apply px-3 py-2 mx-1 border border-emerald-300 rounded-lg text-gray-700 hover:bg-emerald-50 hover:border-emerald-400;
        }

        .dataTables_paginate .paginate_button.current {
            @apply bg-emerald-500 text-white border-emerald-500 hover:bg-emerald-600;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            @apply mb-4;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            @apply border border-emerald-300 rounded-lg px-3 py-2 text-sm min-w-0;
        }

        .dataTables_wrapper .dataTables_paginate {
            @apply pt-4;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply px-3 py-2 mx-1 border border-emerald-300 rounded text-sm hover:bg-emerald-50 transition-colors;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-emerald-600 text-white border-emerald-600;
        }

        tbody tr:hover {
            @apply bg-emerald-50;
        }
    </style>
</body>

</html>
