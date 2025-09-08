<x-guest-layout>
    <div class="min-h-screen flex">
        <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-20 xl:px-24 bg-white">
            <div class="mx-auto w-full max-w-sm lg:w-96">
               <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl transform hover:scale-105 transition-transform">
                        <span class="text-3xl font-bold text-white font-poppins tracking-wider">GO</span>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Masuk ke Sistem</h2>
                    <p class="mt-2 text-sm text-gray-600">Kelola kantin sekolah dengan mudah</p>
                </div>
                @if (session('status'))
                    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200">
                        <p class="font-medium text-sm text-green-700 text-center">
                            {{ session('status') }}
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input id="email"
                                   class="block w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   autocomplete="username"
                                   placeholder="Masukkan email Anda" />
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password"
                                   class="block w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Masukkan password Anda" />
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me"
                                   name="remember"
                                   type="checkbox"
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded transition-colors duration-200">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                Ingat saya
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}"
                                   class="font-medium text-green-600 hover:text-green-500 transition-colors duration-200 hover:underline">
                                    Lupa password?
                                </a>
                            </div>
                        @endif
                    </div>
                    <div>
                        <button type="submit"
                                class="group w-full flex justify-center items-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                            <svg class="h-5 w-5 mr-2 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                            Masuk
                        </button>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Belum punya akun?
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="font-semibold text-green-600 hover:text-green-500 transition-colors duration-200 hover:underline ml-1">
                                    Daftar sekarang
                                </a>
                            @else
                                <span class="font-semibold text-green-600 ml-1">Hubungi administrator</span>
                            @endif
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <div class="hidden lg:block relative flex-1 bg-gradient-to-br from-green-500 via-green-600 to-green-700">
            <div class="absolute inset-0">
                <div class="absolute top-20 right-20 w-32 h-32 bg-white opacity-10 rounded-full animate-pulse"></div>
                <div class="absolute top-40 left-10 w-20 h-20 bg-white opacity-10 rounded-full animate-pulse delay-300"></div>
                <div class="absolute bottom-40 right-40 w-16 h-16 bg-white opacity-10 rounded-full animate-pulse delay-700"></div>
                <div class="absolute bottom-20 left-20 w-40 h-40 bg-white opacity-10 rounded-full animate-pulse delay-500"></div>

                <div class="absolute inset-0 opacity-5">
                    <div class="h-full w-full" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 30px 30px;"></div>
                </div>
            </div>
            <div class="relative z-10 flex flex-col justify-center items-center h-full text-center px-12 text-white">
                <h1 class="text-5xl font-bold mb-2 leading-tight">
                    GoMakan
                </h1>
                <h2 class="text-2xl font-light mb-6 opacity-90">
                    Selamat Datang!
                </h2>

                <p class="text-xl opacity-90 mb-12 max-w-md leading-relaxed">
                    Sistem manajemen kantin sekolah yang memudahkan pengelolaan menu, pesanan, dan keuangan
                </p>
                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div class="bg-white bg-opacity-20 rounded-2xl p-6 backdrop-blur-sm border border-white border-opacity-30 hover:bg-opacity-25 transition-all duration-200">
                        <div class="text-3xl font-bold mb-2">500+</div>
                        <div class="text-sm opacity-80">Menu Tersedia</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-2xl p-6 backdrop-blur-sm border border-white border-opacity-30 hover:bg-opacity-25 transition-all duration-200">
                        <div class="text-3xl font-bold mb-2">98%</div>
                        <div class="text-sm opacity-80">Kepuasan Siswa</div>
                    </div>
                </div>
                <div class="space-y-4 text-left">
                    <div class="flex items-center bg-white bg-opacity-10 rounded-lg p-3 backdrop-blur-sm">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-300 rounded-full flex items-center justify-center mr-3">
                            <svg class="h-4 w-4 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Manajemen Menu Digital</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-10 rounded-lg p-3 backdrop-blur-sm">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-300 rounded-full flex items-center justify-center mr-3">
                            <svg class="h-4 w-4 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Sistem Pembayaran Cashless</span>
                    </div>
                    <div class="flex items-center bg-white bg-opacity-10 rounded-lg p-3 backdrop-blur-sm">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-300 rounded-full flex items-center justify-center mr-3">
                            <svg class="h-4 w-4 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Laporan Real-time</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
