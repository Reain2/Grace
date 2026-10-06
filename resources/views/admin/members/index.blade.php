<x-app-layout>
    <x-slot name="header"><p class="heritage-label">Moderasi</p><h1 class="mt-2 text-3xl font-semibold">Anggota {{ auth()->user()->tradition?->name }}</h1><p class="mt-3" style="color: var(--heritage-muted)">Kelola status akses user dari Agamamu.</p></x-slot>
    <div class="heritage-container py-10">
        @if (session('status')) <p class="mb-5 border border-green-700 bg-green-50 p-3 text-sm text-green-900">{{ session('status') }}</p> @endif
        <div class="space-y-4">
            @forelse ($members as $member)
                <article class="heritage-surface flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between"><div><h2 class="font-semibold">{{ $member->name }}</h2><p class="mt-1 text-sm" style="color: var(--heritage-muted)">{{ $member->email }} · {{ $member->verification_status->value }}</p></div><form method="POST" action="{{ route('admin.members.toggle', $member) }}">@csrf <button class="heritage-button-secondary">{{ $member->status->value === 'active' ? 'Tangguhkan' : 'Aktifkan' }}</button></form></article>
            @empty <div class="heritage-surface p-6">Belum ada anggota di Agama ini.</div> @endforelse
        </div>
        <div class="mt-6">{{ $members->links() }}</div>
    </div>
</x-app-layout>
