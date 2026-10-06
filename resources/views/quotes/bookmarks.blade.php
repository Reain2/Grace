<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Kutipan tersimpan</h2></x-slot>
    <div class="py-12"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @forelse ($quotes as $quote)
            <article class="bg-white p-6 shadow-sm sm:rounded-lg"><p class="text-lg">“{{ $quote->body }}”</p><p class="mt-3 text-sm text-gray-600">{{ $quote->source }}</p><form method="POST" action="{{ route('user.quotes.bookmark', $quote) }}" class="mt-4">@csrf <x-secondary-button>Hapus simpanan</x-secondary-button></form></article>
        @empty
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">Belum ada kutipan tersimpan.</div>
        @endforelse
        {{ $quotes->links() }}
    </div></div>
</x-app-layout>
