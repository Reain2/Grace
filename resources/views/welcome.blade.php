<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Grace · Ruang kehidupan rohani</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .landing-orbit { animation: orbit 18s linear infinite; transform-origin: 50% 50%; }
        .landing-breathe { animation: breathe 5s ease-in-out infinite; }
        @keyframes orbit { to { transform: rotate(360deg); } }
        @keyframes breathe { 0%, 100% { transform: scale(1); opacity: .75; } 50% { transform: scale(1.06); opacity: 1; } }
        @media (prefers-reduced-motion: reduce) { .landing-orbit, .landing-breathe { animation: none; } }
    </style>
</head>
<body style="background: var(--heritage-paper); color: var(--heritage-ink)">
    <a href="#landing-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:bg-white focus:px-4 focus:py-3">Lewati ke konten</a>
    <div class="min-h-screen">
        <header class="heritage-container flex min-h-20 items-center justify-between">
            <a href="/" class="flex items-center gap-3 text-lg font-semibold"><span class="grid h-9 w-9 place-items-center border" style="border-color:var(--heritage-ink)">G</span>Grace</a>
            <nav class="flex items-center gap-2 sm:gap-4"><a href="{{ route('login') }}" class="rounded px-3 py-2 text-sm font-semibold underline">Masuk</a><a href="{{ route('register') }}" class="heritage-button-primary">Mulai</a></nav>
        </header>
        <main id="landing-content">
            <section class="heritage-container grid min-h-[calc(100vh-5rem)] items-center gap-12 py-12 lg:grid-cols-[1.05fr_.95fr] lg:py-20">
                <div><p class="heritage-label">Ruang lintas tradisi</p><h1 class="mt-5 max-w-3xl text-5xl font-semibold leading-[1.05] tracking-tight sm:text-7xl">Jalani yang bermakna, satu hari pada satu waktu.</h1><p class="mt-7 max-w-xl text-lg leading-8" style="color:var(--heritage-muted)">Grace membantu menjaga ritme refleksi, bacaan, kutipan, dan kegiatan sesuai tradisi yang kamu pilih.</p><div class="mt-9 flex flex-wrap gap-3"><a href="{{ route('register') }}" class="heritage-button-primary">Buat ruang pribadi</a><a href="{{ route('login') }}" class="heritage-button-secondary">Masuk ke Grace</a></div><p class="mt-6 text-sm" style="color:var(--heritage-muted)">Konten demo. Data awal dapat diganti oleh admin tradisi.</p></div>
                <div class="relative mx-auto aspect-square w-full max-w-md" aria-label="Ilustrasi ritme harian Grace"><div class="landing-breathe absolute inset-[16%] rounded-full" style="background:var(--heritage-accent)"></div><div class="landing-orbit absolute inset-[8%] rounded-full border" style="border-color:var(--heritage-ink)"><span class="absolute left-1/2 top-0 h-4 w-4 -translate-x-1/2 -translate-y-1/2 rounded-full" style="background:var(--heritage-ink)"></span></div><div class="absolute inset-[25%] grid place-items-center border text-center" style="border-color:var(--heritage-ink); background:var(--heritage-surface)"><div><p class="heritage-label">Hari ini</p><p class="mt-3 text-3xl font-semibold">Ruang<br>untuk hadir.</p></div></div><p class="absolute bottom-4 left-0 text-sm font-semibold" style="color:var(--heritage-muted)">Baca · Refleksi · Terhubung</p></div>
            </section>
            <section class="border-y" style="border-color:var(--heritage-border); background:var(--heritage-surface)"><div class="heritage-container grid gap-10 py-20 md:grid-cols-[.8fr_1.2fr] md:items-start"><div><p class="heritage-label">Satu ruang, beberapa ritme</p><h2 class="mt-4 text-4xl font-semibold">Dibuat untuk dipakai perlahan.</h2></div><div class="grid gap-6 sm:grid-cols-3"><article><p class="text-2xl font-semibold">01</p><h3 class="mt-3 text-xl font-semibold">Temukan</h3><p class="mt-2 text-sm leading-6" style="color:var(--heritage-muted)">Kutipan, bacaan, dan kegiatan sesuai tradisi pilihanmu.</p></article><article><p class="text-2xl font-semibold">02</p><h3 class="mt-3 text-xl font-semibold">Jalani</h3><p class="mt-2 text-sm leading-6" style="color:var(--heritage-muted)">Bangun ritme dengan pengingat, progress, dan streak tanpa tekanan.</p></article><article><p class="text-2xl font-semibold">03</p><h3 class="mt-3 text-xl font-semibold">Catat</h3><p class="mt-2 text-sm leading-6" style="color:var(--heritage-muted)">Simpan refleksi pribadi di ruang jurnal yang hanya milikmu.</p></article></div></div></section>
            <section class="heritage-container py-20"><div class="max-w-2xl"><p class="heritage-label">Mulai dari langkah kecil</p><h2 class="mt-4 text-4xl font-semibold">Pilih cara yang terasa tepat untukmu.</h2><p class="mt-4 leading-7" style="color:var(--heritage-muted)">Buat akun dengan tradisi pilihanmu. Untuk demo ini, gunakan data dan foto dummy saja.</p><div class="mt-7 flex flex-wrap gap-3"><a href="{{ route('register') }}" class="heritage-button-primary">Buat akun</a><a href="{{ route('login') }}" class="heritage-button-secondary">Saya sudah punya akun</a></div></div></section>
        </main>
        <footer class="border-t" style="border-color:var(--heritage-border)"><div class="heritage-container flex flex-col gap-2 py-8 text-sm sm:flex-row sm:items-center sm:justify-between" style="color:var(--heritage-muted)"><span>Grace · Demo lokal</span><span>Ruang lintas tradisi dengan pilihan di tanganmu.</span></div></footer>
    </div>
</body>
</html>
