{{-- resources/views/menus/reviews.blade.php --}}
@extends('layouts.user')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header Menu Info --}}
        <div class="bg-white rounded-2xl shadow-sm mb-8 overflow-hidden">
            <div class="md:flex">
                <div class="md:flex-shrink-0">
                    @if ($menu->image)
                        <img class="h-48 w-full object-cover md:w-48"
                             src="{{ asset('storage/' . $menu->image) }}"
                             alt="{{ $menu->name }}">
                    @else
                        <div class="h-48 w-full md:w-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-4xl mb-2">🍽️</div>
                                <span class="text-gray-500 text-sm">No Image</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">
                        {{ \App\Constant::MENU_CATEGORIES[$menu->category] ?? 'Tidak Diketahui' }}
                    </div>
                    <h1 class="mt-2 text-3xl font-bold text-gray-900">{{ $menu->name }}</h1>
                    @if ($menu->description)
                        <p class="mt-2 text-gray-600">{{ $menu->description }}</p>
                    @endif
                    <div class="mt-4">
                        <span class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            Rp {{ number_format($menu->price, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Overall Rating Summary --}}
                    @if ($menu->totalRatings() > 0)
                        <div class="mt-4 flex items-center">
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= floor($menu->averageRating()) ? 'text-yellow-400' : 'text-gray-300' }} fill-current"
                                        viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-lg font-semibold text-gray-700">
                                {{ number_format($menu->averageRating(), 1) }}
                            </span>
                            <span class="ml-1 text-gray-500">
                                dari {{ $menu->totalRatings() }} ulasan
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="mb-6">
            <button onclick="history.back()"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </button>
        </div>

        {{-- Reviews Section --}}
        <div class="bg-white rounded-2xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">
                    Semua Ulasan ({{ $menu->totalRatings() }})
                </h2>
            </div>

            @if ($menu->ratings->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach ($menu->ratings as $rating)
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                {{-- User Avatar --}}
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ strtoupper(substr($rating->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Review Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900">
                                                {{ $rating->user->name }}
                                            </h4>
                                            <p class="text-sm text-gray-500">
                                                {{ $rating->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>

                                        {{-- Rating Stars --}}
                                        <div class="flex items-center space-x-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current"
                                                    viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                                </svg>
                                            @endfor
                                            <span class="ml-1 text-sm font-medium text-gray-600">
                                                {{ $rating->rating }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Review Text --}}
                                    @if ($rating->review_text)
                                        <div class="mt-3">
                                            <p class="text-gray-700 text-sm leading-relaxed">
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
                {{-- Empty State --}}
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">📝</div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Ulasan</h3>
                    <p class="text-gray-500">Jadilah yang pertama memberikan ulasan untuk menu ini!</p>
                </div>
            @endif
        </div>

        {{-- Rating Statistics (Optional Enhancement) --}}
        @if ($menu->totalRatings() > 0)
            <div class="mt-8 bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Rating</h3>
                <div class="space-y-3">
                    @for ($star = 5; $star >= 1; $star--)
                        @php
                            $count = $menu->ratings->where('rating', $star)->count();
                            $percentage = $menu->totalRatings() > 0 ? ($count / $menu->totalRatings()) * 100 : 0;
                        @endphp
                        <div class="flex items-center">
                            <div class="flex items-center w-20">
                                <span class="text-sm font-medium text-gray-600 mr-2">{{ $star }}</span>
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L0 6.91l6.564-.955L10 0l3.436 5.955L20 6.91l-5.245 4.635L15.878 18z" />
                                </svg>
                            </div>
                            <div class="flex-1 mx-4">
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 w-12 text-right">
                                {{ $count }}
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
