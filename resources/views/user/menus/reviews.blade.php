@extends('layouts.user')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-3xl shadow-lg mb-8 overflow-hidden">
                <div class="flex flex-col md:flex-row h-auto md:h-64">
                    <div class="md:w-80 flex-shrink-0 relative">
                        @if ($menu->image)
                            <img class="w-full h-64 md:h-full object-cover"
                                 src="{{ asset('storage/' . $menu->image) }}"
                                 alt="{{ $menu->name }}">
                            <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent md:hidden"></div>
                        @else
                            <div class="w-full h-64 md:h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-6xl mb-3 opacity-60">🍽️</div>
                                    <span class="text-gray-400 text-sm font-medium">No Image Available</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 p-8 flex flex-col justify-center">
                        <div class="inline-flex items-center mb-3">
                            <span class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide uppercase shadow-sm">
                                {{ \App\Constant::MENU_CATEGORIES[$menu->category] ?? 'Tidak Diketahui' }}
                            </span>
                        </div>

                        <h1 class="text-4xl font-bold text-gray-900 mb-3 leading-tight">
                            {{ $menu->name }}
                        </h1>

                        @if ($menu->description)
                            <p class="text-gray-600 mb-4 text-lg leading-relaxed">
                                {{ $menu->description }}
                            </p>
                        @endif

                        <div class="mb-4">
                            <span class="text-3xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </span>
                        </div>

                        @if ($menu->totalRatings() > 0)
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-1">
                                    @for ($i = 1; $i <= $menu->full_stars; $i++)
                                        <svg class="w-6 h-6 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                        </svg>
                                    @endfor

                                    @if ($menu->has_half_star)
                                        <div class="relative w-6 h-6">
                                            <svg class="absolute w-6 h-6 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                            </svg>
                                            <svg class="absolute w-6 h-6 text-yellow-400 fill-current" viewBox="0 0 20 20" style="clip-path: inset(0 50% 0 0);">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                            </svg>
                                        </div>
                                    @endif

                                    @for ($i = 1; $i <= $menu->empty_stars; $i++)
                                        <svg class="w-6 h-6 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                        </svg>
                                    @endfor
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-2xl font-bold text-gray-800">
                                        {{ number_format($menu->averageRating(), 1) }}
                                    </span>
                                    <div class="text-gray-500">
                                        <span class="font-medium">{{ $menu->totalRatings() }}</span>
                                        <span class="text-sm">ulasan</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="flex space-x-1 opacity-30">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-6 h-6 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="ml-3 text-gray-400 font-medium">Belum ada rating</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <button onclick="history.back()"
                    class="group inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="font-medium">Kembali</span>
                </button>
            </div>

            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900">
                            Semua Ulasan
                        </h2>
                        <span class="bg-indigo-100 text-indigo-800 px-4 py-2 rounded-full font-semibold text-sm">
                            {{ $menu->totalRatings() }} ulasan
                        </span>
                    </div>
                </div>

                @if ($menu->ratings->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach ($menu->ratings as $rating)
                            <div class="p-8 hover:bg-gray-50/50 transition-colors duration-200">
                                <div class="flex items-start space-x-5">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center shadow-lg">
                                            <span class="text-white font-bold text-lg">
                                                {{ strtoupper(substr($rating->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900">
                                                    {{ $rating->user->name }}
                                                </h4>
                                                <p class="text-sm text-gray-500 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $rating->created_at->format('d M Y, H:i') }}
                                                </p>
                                            </div>

                                            <div class="flex items-center space-x-2 bg-yellow-50 px-3 py-2 rounded-full">
                                                <div class="flex items-center space-x-1">
                                                    @for ($i = 1; $i <= $rating->full_stars; $i++)
                                                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                            <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                                        </svg>
                                                    @endfor

                                                    @if ($rating->has_half_star)
                                                        <div class="relative w-4 h-4">
                                                            <svg class="absolute w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                                            </svg>
                                                            <svg class="absolute w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20" style="clip-path: inset(0 50% 0 0);">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                                            </svg>
                                                        </div>
                                                    @endif

                                                    @for ($i = 1; $i <= $rating->empty_stars; $i++)
                                                        <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                            <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-sm font-bold text-gray-700">
                                                    {{ $rating->rating }}.0
                                                </span>
                                            </div>
                                        </div>

                                        @if ($rating->review_text)
                                            <div class="bg-gray-50 rounded-xl p-4 mt-3">
                                                <p class="text-gray-700 leading-relaxed">
                                                    {{ $rating->review_text }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-16 text-center">
                        <div class="text-8xl mb-6 opacity-60">📝</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Ulasan</h3>
                        <p class="text-gray-500 text-lg max-w-md mx-auto">
                            Jadilah yang pertama memberikan ulasan untuk menu lezat ini!
                        </p>
                    </div>
                @endif
            </div>

            @if ($menu->totalRatings() > 0)
                <div class="mt-8 bg-white rounded-3xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Statistik Rating
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            @for ($star = 5; $star >= 1; $star--)
                                @php
                                    $count = $menu->ratings->where('rating', $star)->count();
                                    $percentage = $menu->totalRatings() > 0 ? ($count / $menu->totalRatings()) * 100 : 0;
                                @endphp
                                <div class="flex items-center group">
                                    <div class="flex items-center w-16">
                                        <span class="text-sm font-semibold text-gray-600 mr-2">{{ $star }}</span>
                                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 mx-4">
                                        <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                                            <div class="bg-gradient-to-r from-yellow-400 to-orange-400 h-3 rounded-full transition-all duration-1000 group-hover:from-yellow-500 group-hover:to-orange-500"
                                                 style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-600 w-12 text-right">
                                        {{ $count }}
                                    </div>
                                    <div class="text-xs text-gray-400 w-12 text-right ml-2">
                                        {{ number_format($percentage, 0) }}%
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6">
                            <div class="text-center">
                                <div class="text-5xl font-bold text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text mb-2">
                                    {{ number_format($menu->averageRating(), 1) }}
                                </div>
                                <div class="flex justify-center mb-3 space-x-1">
                                    @for ($i = 1; $i <= $menu->full_stars; $i++)
                                        <svg class="w-6 h-6 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                        </svg>
                                    @endfor

                                    @if ($menu->has_half_star)
                                        <div class="relative w-6 h-6">
                                            <svg class="absolute w-6 h-6 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                            </svg>
                                            <svg class="absolute w-6 h-6 text-yellow-400 fill-current" viewBox="0 0 20 20" style="clip-path: inset(0 50% 0 0);">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                            </svg>
                                        </div>
                                    @endif

                                    @for ($i = 1; $i <= $menu->empty_stars; $i++)
                                        <svg class="w-6 h-6 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-gray-600 font-medium">
                                    Berdasarkan {{ $menu->totalRatings() }} ulasan
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
