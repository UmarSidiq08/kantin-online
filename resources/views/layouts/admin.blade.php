<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>

    {{-- Bootstrap & DataTables CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">


    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out'
                    }
                }
            }
        }
    </script>
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

        /* Ensure Tailwind doesn't conflict with Bootstrap */
        .container {
            @apply !max-w-none !mx-0 !px-0;
        }
    </style>
    @push('styles')
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <style>
            /* Custom DataTables styling to work with Tailwind */
            .dataTables_wrapper {
                font-family: inherit;
            }

            .dataTables_filter input {
                @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
            }

            .dataTables_length select {
                @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
            }

            .dataTables_info {
                @apply text-sm text-gray-600;
            }

            .dataTables_paginate .paginate_button {
                @apply px-3 py-2 mx-1 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50;
            }

            .dataTables_paginate .paginate_button.current {
                @apply bg-blue-500 text-white border-blue-500;
            }
        </style>
        <style>

        </style>
        <style>
            
            /* DataTables Custom Styling */
            .dataTables_wrapper {
                @apply px-6 pb-6;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                @apply mb-4;
            }

            .dataTables_wrapper .dataTables_length select,
            .dataTables_wrapper .dataTables_filter input {
                @apply border border-gray-300 rounded-lg px-3 py-2 text-sm min-w-0;
            }

            .dataTables_wrapper .dataTables_info {
                @apply text-sm text-gray-600 py-4;
            }

            .dataTables_wrapper .dataTables_paginate {
                @apply pt-4;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                @apply px-3 py-2 mx-1 border border-gray-300 rounded text-sm hover:bg-gray-50 transition-colors;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                @apply bg-blue-600 text-white border-blue-600;
            }

            /* Table Row Hover Effect */
            tbody tr:hover {
                @apply bg-gray-50;
            }

            /* Smooth transitions */
            * {
                transition: all 0.2s ease-in-out;
            }
        </style>
    @endpush
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="bg-gray-100 text-gray-900">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <div class="w-64 bg-gradient-to-b from-slate-800 to-slate-900 text-white fixed h-full overflow-y-auto z-50">
            <!-- Logo -->
            <div class="p-6 border-b border-slate-700">
                <h2 class="text-xl font-bold text-center">Admin Kantin</h2>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-6">
                <ul class="space-y-2 px-4">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center px-4 py-3 text-gray-300 hover:bg-slate-700 hover:text-white rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'border-l-4 border-blue-500 bg-slate-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.menu.index') }}"
                            class="flex items-center px-4 py-3 text-gray-300 hover:bg-slate-700 hover:text-white rounded-lg transition-all duration-200 {{ request()->routeIs('admin.menu*') ? 'border-l-4 border-blue-500 bg-slate-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Menu Kantin
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}"
                            class="flex items-center px-4 py-3 text-gray-300 hover:bg-slate-700 hover:text-white rounded-lg transition-all duration-200 {{ request()->routeIs('admin.orders*') ? 'border-l-4 border-blue-500 bg-slate-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                </path>
                            </svg>
                            Daftar Pesanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.logs') }}"
                            class="flex items-center px-4 py-3 text-gray-300 hover:bg-slate-700 hover:text-white rounded-lg transition-all duration-200 {{ request()->routeIs('admin.logs*') ? 'border-l-4 border-blue-500 bg-slate-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                </path>
                            </svg>
                            Riwayat Pesanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.laporan.index') }}"
                            class="flex items-center px-4 py-3 text-gray-300 hover:bg-slate-700 hover:text-white rounded-lg transition-all duration-200 {{ request()->routeIs('admin.laporan.index*') ? 'border-l-4 border-blue-500 bg-slate-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                </path>
                            </svg>
                            Laporan Penjualan
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Logout Button -->
            <div class="absolute bottom-6 left-4 right-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-3 text-gray-300 hover:bg-red-600 hover:text-white rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="flex-1 ml-64">
            {{-- Navbar (Optional/Override) --}}
            @hasSection('navbar')
                @yield('navbar')
            @else
                <nav class="bg-white shadow-sm p-4 text-gray-800 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <x-page-header />
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </nav>
            @endif

            {{-- Konten utama --}}
            <div class="p-6">
                @yield('modal') {{-- Wajib agar modal muncul --}}
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    {{-- Sidebar Navigation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Highlight menu aktif berdasarkan URL
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar nav a');

            navLinks.forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (currentPath.startsWith(linkPath) && linkPath !== '/') {
                    link.classList.add('border-l-4', 'border-blue-500', 'bg-slate-700', 'text-white');
                    link.classList.remove('text-gray-300');
                }
            });
        });
    </script>

    @yield('script') {{-- Untuk script JS dari halaman --}}
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", 'Berhasil 🚀');
        </script>
    @endif
</body>
@stack('scripts')

</html>
