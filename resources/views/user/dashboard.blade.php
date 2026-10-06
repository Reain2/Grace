<x-app-layout>
    <x-slot name="header">
        <p class="heritage-label">Ruang pribadi</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Selamat datang, {{ auth()->user()->name }}.</h1>
        <p class="mt-3 max-w-2xl text-base" style="color: var(--heritage-muted)">Ruang tenang untuk membaca, menyimpan, dan melanjutkan perjalanan rohani dari {{ auth()->user()->tradition?->name ?? 'Agama pilihanmu' }}.</p>
    </x-slot>
    <div class="heritage-container grid gap-6 py-10 lg:grid-cols-[1.35fr_.65fr]">
        <section class="heritage-surface p-6 sm:p-8">
            <p class="heritage-label">Mulai dari sini</p>
            <h2 class="mt-3 text-2xl font-semibold">Bacaan yang sesuai ritmemu</h2>
            <p class="mt-3 max-w-xl leading-7" style="color: var(--heritage-muted)">Pilih rencana bacaan dan tandai bagian yang sudah selesai. Tidak perlu mengejar angka, cukup lanjutkan satu langkah hari ini.</p>
            @if (auth()->user()->verification_status->value === 'approved')
                <div class="mt-7 flex flex-wrap gap-3"><a href="{{ route('user.reading-plans.index') }}" class="heritage-button-primary">Lihat rencana bacaan</a><a href="{{ route('user.quotes.index') }}" class="heritage-button-secondary">Baca kutipan</a></div>
            @else
                <div class="mt-7 border p-4" style="border-color: #b08a36; background: #fff8e7"><p class="font-semibold">Akun menunggu verifikasi</p><p class="mt-1 text-sm">Halaman dapat dilihat, tetapi tindakan seperti menyimpan dan menandai bacaan terkunci.</p><a href="{{ route('verification.status') }}" class="mt-3 inline-block font-semibold underline">Lihat status verifikasi</a></div>
            @endif
        </section>
        <aside class="border p-6 sm:p-8" style="border-color: var(--heritage-ink); background: var(--heritage-ink); color: var(--heritage-surface)">
            <p class="heritage-label" style="color: var(--heritage-accent)">Agama</p>
            <h2 class="mt-3 text-2xl font-semibold">{{ auth()->user()->tradition?->name ?? 'Belum dipilih' }}</h2>
            <p class="mt-4 text-sm leading-6" style="color: #e8e6e3">Konten di ruang ini mengikuti Agama yang terhubung dengan akunmu.</p>
        </aside>
    </div>
</x-app-layout>
