<x-app-layout>
    <x-slot name="header"><p class="heritage-label">Pengaturan akun</p><h1 class="mt-2 text-3xl font-semibold">Profil</h1><p class="mt-3" style="color: var(--heritage-muted)">Kelola identitas login dan keamanan akunmu.</p></x-slot>
    <div class="heritage-container space-y-6 py-10">
        @if (session('status') === 'tradition-change-submitted')<p class="border border-amber-700 bg-amber-50 p-3 text-sm text-amber-950">Perubahan Agama dikirim. Interaksi terkunci sampai admin Agama baru menyetujui verifikasi.</p>@endif
        <section class="heritage-surface p-6 sm:p-8"><div class="max-w-xl">@include('profile.partials.update-profile-information-form')</div></section>
        <section class="heritage-surface p-6 sm:p-8"><div class="max-w-xl">@include('profile.partials.update-password-form')</div></section>
        <section class="border border-red-800 bg-red-50 p-6 sm:p-8"><div class="max-w-xl">@include('profile.partials.delete-user-form')</div></section>
    </div>
</x-app-layout>
