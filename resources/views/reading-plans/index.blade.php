<x-app-layout>
    <x-slot name="header"><p class="heritage-label">Ritme membaca</p><h1 class="mt-2 text-3xl font-semibold">Rencana bacaan</h1><p class="mt-3" style="color: var(--heritage-muted)">Pilih satu rencana yang terasa mungkin dijalani hari ini.</p></x-slot>
    <div class="heritage-container py-10"><div class="grid gap-5 lg:grid-cols-2">
        @forelse ($plans as $plan)
            <article class="heritage-surface flex flex-col p-6 sm:p-8"><div class="flex-1"><p class="heritage-label">{{ $plan->total_days }} hari</p><h2 class="mt-3 text-2xl font-semibold">{{ $plan->title }}</h2><p class="mt-3 leading-7" style="color: var(--heritage-muted)">{{ $plan->description }}</p><p class="mt-5 text-sm font-semibold">Sumber: {{ $plan->source }}</p><ol class="mt-5 space-y-2 border-t pt-5 text-sm" style="border-color: var(--heritage-border)">@foreach ($plan->items as $item)<li><span class="font-semibold">{{ $item->title }}</span><span style="color: var(--heritage-muted)">, {{ $item->reference }}</span></li>@endforeach</ol></div><form method="POST" action="{{ route('user.reading-plans.start', $plan) }}" class="mt-7">@csrf <button class="heritage-button-primary">Mulai rencana</button></form></article>
        @empty <div class="heritage-surface p-6"><h2 class="text-xl font-semibold">Belum ada rencana bacaan.</h2><p class="mt-2" style="color: var(--heritage-muted)">Admin Agamamu belum menerbitkan rencana. Coba lagi setelah konten tersedia.</p></div> @endforelse
    </div><div class="mt-6">{{ $plans->links() }}</div></div>
</x-app-layout>
