<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Kantin Online</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="container mx-auto p-4">
        <header class="mb-6">
            <h1 class="text-2xl font-bold">@yield('title')</h1>
        </header>

        <main>
            @hasSection('navbar')
                @yield('navbar')
            @endif

            @yield('content')
        </main>
    </div>

</body>

</html>
