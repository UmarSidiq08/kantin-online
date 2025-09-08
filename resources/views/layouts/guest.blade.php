<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GoMakan') }} - Sistem Kantin Sekolah</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Favicon -->
   <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:%2310b981;stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:%23059669;stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='32' height='32' rx='8' fill='url(%23grad)'/%3E%3Ctext x='16' y='22' text-anchor='middle' font-family='Arial, sans-serif' font-size='14' font-weight='bold' fill='white'%3EGO%3C/text%3E%3C/svg%3E">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        /* Fix untuk mencegah duplikasi elemen */
        .login-container {
            position: relative;
            z-index: 1;
        }

        /* Smooth animations */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Prevent layout shift */
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .logo-icon {
            flex-shrink: 0;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="login-page fade-in">
        {{ $slot }}
    </div>
</body>

</html>
