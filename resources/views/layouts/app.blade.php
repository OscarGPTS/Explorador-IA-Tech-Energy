<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('styles')

        <style>
            :root {
                --eia-black: #0F1419;
                --eia-graphite: #1F2937;
                --eia-slate: #475569;
                --eia-mute: #64748B;
                --eia-border: #E5E7EB;
                --eia-surface: #FFFFFF;
                --eia-bg: #F8FAFC;
                --eia-red: #B91C1C;
                --eia-gold: #D97706;
                --eia-gold-soft: #FBBF24;
            }
            body { background: #F8FAFC; }
            .eia-app-shell { min-height: 100vh; background: #F8FAFC; }
            .eia-app-main { padding-top: 64px; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="eia-app-shell">

            @include('components.topbar')

            <main class="eia-app-main">
                @yield('content')
            </main>

        </div>

        {{-- @include('components.corporate-chat-widget') --}}

        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        @livewireScripts
        @stack('scripts')

    </body>
</html>
