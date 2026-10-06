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
        <main id="main-content" class="heritage-shell flex min-h-screen items-center justify-center px-4 py-10">
            <div class="w-full max-w-md">
                <a href="/" class="mb-8 flex items-center justify-center gap-3 text-xl font-semibold"><span class="grid h-10 w-10 place-items-center border" style="border-color: var(--heritage-ink)">G</span>Grace</a>
                <section class="heritage-surface p-6 sm:p-8">{{ $slot }}</section>
            </div>
        </main>
    </body>
</html>
