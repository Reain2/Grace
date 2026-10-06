<x-app-layout>
    <x-slot name="header"><p class="heritage-label">Jejak sistem</p><h1 class="mt-2 text-3xl font-semibold">Audit log</h1><p class="mt-3" style="color: var(--heritage-muted)">Catatan aksi administratif yang tercatat.</p></x-slot>
    <div class="heritage-container py-10"><div class="space-y-4">
        @forelse ($logs as $log)
            <article class="heritage-surface p-6"><div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between"><div><p class="font-semibold">{{ $log->action }}</p><p class="mt-1 text-sm" style="color: var(--heritage-muted)">{{ $log->actor?->name ?? 'Sistem' }} · {{ $log->target_type }} #{{ $log->target_id }}</p></div><time class="text-sm" style="color: var(--heritage-muted)">{{ $log->created_at->format('d M Y H:i') }}</time></div>@if ($log->meta)<pre class="mt-4 overflow-auto border p-3 text-xs" style="border-color: var(--heritage-border)">{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>@endif</article>
        @empty <div class="heritage-surface p-6">Belum ada aktivitas administratif.</div>@endforelse
    </div><div class="mt-6">{{ $logs->links() }}</div></div>
</x-app-layout>
