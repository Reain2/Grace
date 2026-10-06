<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Grace') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:bg-white focus:px-4 focus:py-3">Lewati ke konten</a>
        <div class="heritage-shell">
            @include('layouts.navigation')
            @isset($header)
                <header class="border-b" style="border-color: var(--heritage-border); background: var(--heritage-surface)">
                    <div class="heritage-container py-8">{{ $header }}</div>
                </header>
            @endisset
            <main id="main-content">{{ $slot }}</main>
        </div>
    </body>
</html>
