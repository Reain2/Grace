<x-app-layout>
    <x-slot name="header"><p class="heritage-label">Ruang pengelola</p><h1 class="mt-2 text-3xl font-semibold">{{ auth()->user()->tradition?->name }}.</h1><p class="mt-3" style="color: var(--heritage-muted)">Tinjau pendaftar dan rawat konten Agama secara bertanggung jawab.</p></x-slot>
    <div class="heritage-container grid gap-6 py-10 md:grid-cols-2">
        <a href="{{ route('admin.verifications.index') }}" class="heritage-surface block p-6 transition hover:border-current"><p class="heritage-label">Prioritas</p><h2 class="mt-3 text-xl font-semibold">Tinjau verifikasi</h2><p class="mt-2 text-sm leading-6" style="color: var(--heritage-muted)">Periksa antrean pendaftar Agamamu dan ambil keputusan dengan alasan yang jelas.</p></a>
        <a href="{{ route('admin.members.index') }}" class="heritage-surface block p-6 transition hover:border-current"><p class="heritage-label">Anggota</p><h2 class="mt-3 text-xl font-semibold">Moderasi user</h2><p class="mt-2 text-sm leading-6" style="color: var(--heritage-muted)">Lihat anggota Agamamu dan kelola status akses.</p></a>
        <a href="{{ route('admin.quotes.index') }}" class="heritage-surface block p-6 transition hover:border-current"><p class="heritage-label">Konten</p><h2 class="mt-3 text-xl font-semibold">Rawat kutipan</h2><p class="mt-2 text-sm leading-6" style="color: var(--heritage-muted)">Tambah kutipan bersumber untuk ruang user.</p></a>
    </div>
</x-app-layout>
