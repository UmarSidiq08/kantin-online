<div class="w-64 bg-gradient-to-b from-slate-800 to-slate-900 text-white fixed h-full overflow-y-auto">
    <!-- Logo -->
    <div class="p-6 border-b border-slate-700">
        <h2 class="text-xl font-bold text-center">Admin Panel</h2>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6">
        <ul class="space-y-2 px-4">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 text-gray-300 hover:bg-slate-700 hover:text-white rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'border-l-4 border-blue-500 bg-slate-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.menu.index') }}"
                   class="flex items-center px-4 py-3 text-gray-300 hover:bg-slate-700 hover:text-white rounded-lg transition-all duration-200 {{ request()->routeIs('admin.products*') ? 'border-l-4 border-blue-500 bg-slate-700 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    Menu Kantin
                </a>
            </li>


        </ul>
    </nav>

    <!-- Logout Button -->
    <div class="absolute bottom-6 left-4 right-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-300 hover:bg-red-600 hover:text-white rounded-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>
