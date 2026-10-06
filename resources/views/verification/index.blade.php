<x-app-layout>
    <x-slot name="header"><p class="heritage-label">Tugas admin</p><h1 class="mt-2 text-3xl font-semibold">Antrean verifikasi</h1><p class="mt-3" style="color: var(--heritage-muted)">Tinjau bukti dummy, lalu pilih keputusan dengan alasan yang dapat dipahami.</p></x-slot>
    <div class="heritage-container py-10">
        @if (session('status')) <p class="mb-5 border border-green-700 bg-green-50 p-3 text-sm text-green-900">{{ session('status') }}</p> @endif
        <div class="space-y-5">@forelse ($verifications as $verification)
            <article class="heritage-surface p-6 sm:p-8"><div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between"><div><p class="heritage-label">{{ $verification->tradition->name }}</p><h2 class="mt-2 text-xl font-semibold">{{ $verification->user->name }}</h2><p class="mt-2 text-sm" style="color: var(--heritage-muted)">{{ str_replace('_', ' ', $verification->status->value ?? $verification->status) }}</p><a class="mt-4 inline-block font-semibold underline" href="{{ route(str_contains(request()->path(), 'superadmin') ? 'superadmin.verifications.proof' : 'admin.verifications.proof', $verification) }}">Lihat proof dummy</a></div><div class="flex flex-wrap gap-2">
                @if ($verification->status->value === 'pending')<form method="POST" action="{{ route(str_contains(request()->path(), 'superadmin') ? 'superadmin.verifications.claim' : 'admin.verifications.claim', $verification) }}">@csrf <button class="heritage-button-primary">Klaim</button></form>@endif
                @if (in_array($verification->status->value, ['in_review', 'needs_superadmin'], true))<form method="POST" action="{{ route(str_contains(request()->path(), 'superadmin') ? 'superadmin.verifications.approve' : 'admin.verifications.approve', $verification) }}">@csrf <button class="heritage-button-primary">Setujui</button></form>@endif
            </div></div>
            @if (in_array($verification->status->value, ['in_review', 'needs_superadmin'], true))<form method="POST" action="{{ route(str_contains(request()->path(), 'superadmin') ? 'superadmin.verifications.reject' : 'admin.verifications.reject', $verification) }}" class="mt-6 flex flex-col gap-3 border-t pt-5 sm:flex-row" style="border-color: var(--heritage-border)">@csrf <input name="reason" required minlength="10" class="block min-h-11 flex-1 border-gray-400" placeholder="Alasan penolakan, minimal 10 karakter"><button class="heritage-button-secondary">Tolak</button></form>@endif
            </article>
        @empty <div class="heritage-surface p-6"><h2 class="text-xl font-semibold">Antrean kosong.</h2><p class="mt-2" style="color: var(--heritage-muted)">Tidak ada pendaftar yang perlu ditinjau saat ini.</p></div>@endforelse</div>
        <div class="mt-6">{{ $verifications->links() }}</div>
    </div>
</x-app-layout>
