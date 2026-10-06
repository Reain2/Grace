<x-app-layout>
    <x-slot name="header"><p class="heritage-label">Koleksi harian</p><h1 class="mt-2 text-3xl font-semibold">Kutipan {{ auth()->user()->tradition?->name }}</h1><p class="mt-3" style="color: var(--heritage-muted)">Simpan kalimat yang ingin kamu bawa sepanjang hari.</p></x-slot>
    <div class="heritage-container py-10"><div class="space-y-5">
        @forelse ($quotes as $quote)
            <article class="heritage-surface max-w-3xl p-6 sm:p-8"><p class="text-2xl leading-relaxed">“{{ $quote->body }}”</p><p class="mt-5 text-sm font-semibold" style="color: var(--heritage-muted)">{{ $quote->source }}</p><form method="POST" action="{{ route('user.quotes.bookmark', $quote) }}" class="mt-6">@csrf <button class="heritage-button-secondary">Simpan kutipan</button></form></article>
        @empty <div class="heritage-surface max-w-3xl p-6"><h2 class="text-xl font-semibold">Belum ada kutipan.</h2><p class="mt-2" style="color: var(--heritage-muted)">Admin Agamamu belum menerbitkan kutipan. Coba lagi setelah konten tersedia.</p></div> @endforelse
        {{ $quotes->links() }}
    </div></div>
</x-app-layout>
