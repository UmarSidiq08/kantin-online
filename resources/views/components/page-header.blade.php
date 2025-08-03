@props(['route'])

@php
    $map = [
        'admin.dashboard' => ['Dashboard', 'Kelola sistem Anda dengan mudah'],
        'admin.menu*' => ['Menu Kantin', 'Kelola menu makanan dan minuman'],
        'admin.orders*' => ['Daftar Pesanan', 'Kelola dan pantau semua pesanan masuk'],
    ];

    foreach ($map as $pattern => [$title, $desc]) {
        if (request()->routeIs($pattern)) {
            $pageTitle = $title;
            $pageDescription = $desc;
            break;
        }
    }

    $pageTitle = $pageTitle ?? 'Dashboard';
    $pageDescription = $pageDescription ?? 'Kelola sistem Anda dengan mudah';
@endphp

<h1 class="text-xl font-semibold">{{ $pageTitle }}</h1>
<p class="text-sm text-gray-600">{{ $pageDescription }}</p>
