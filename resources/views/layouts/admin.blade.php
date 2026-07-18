<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="bielecki">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">

        <title>@yield('title', 'Admin') | Andrew Bielecki</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-base-100 text-base-content antialiased">
        <header class="border-b border-base-300 bg-base-100/95">
            <nav class="navbar mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Admin navigation">
                <div class="navbar-start">
                    <a class="text-sm font-semibold sm:text-base" href="{{ route('admin.dashboard') }}">
                        Andrew Bielecki Admin
                    </a>
                </div>

                <div class="navbar-end">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-sm" type="submit">Logout</button>
                        </form>
                    @endauth
                </div>
            </nav>
        </header>

        <main>
            @yield('content')
        </main>
    </body>
</html>
