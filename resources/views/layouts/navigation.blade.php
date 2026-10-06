<nav x-data="{ open: false }" class="border-b" style="border-color: var(--heritage-border); background: var(--heritage-surface)">
    <div class="heritage-container flex min-h-16 items-center justify-between gap-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-lg font-semibold" style="color: var(--heritage-ink)">
            <span class="grid h-9 w-9 place-items-center border text-sm" style="border-color: var(--heritage-ink)">G</span>
            <span>Grace</span>
        </a>
        <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="primary-navigation" class="heritage-button-secondary md:hidden">Menu</button>
        <div id="primary-navigation" :class="open ? 'block' : 'hidden'" class="absolute left-0 right-0 top-16 z-20 border-b p-4 md:static md:block md:border-0 md:p-0" style="border-color: var(--heritage-border); background: var(--heritage-surface)">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-5">
                <a href="{{ route('dashboard') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Beranda</a>
                @if (auth()->user()->role?->value === 'user' && auth()->user()->verification_status?->value === 'approved')
                    <a href="{{ route('user.quotes.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Kutipan</a>
                    <a href="{{ route('user.quotes.bookmarks') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Tersimpan</a>
                    <a href="{{ route('user.reading-plans.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Bacaan</a>
                    <a href="{{ route('user.events.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Event</a>
                    <a href="{{ route('user.journal.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Jurnal</a>
                    <a href="{{ route('user.holy-days.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Kalender</a>
                    <a href="{{ route('user.notifications.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Notifikasi</a>
                    <a href="{{ route('user.reminders.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Pengingat</a>
                @elseif (auth()->user()->role?->value === 'religion_admin')
                    <a href="{{ route('admin.verifications.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Verifikasi</a>
                    <a href="{{ route('admin.members.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Anggota</a>
                    <a href="{{ route('admin.quotes.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Kutipan</a>
                    <a href="{{ route('admin.reading-plans.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Bacaan</a>
                    <a href="{{ route('admin.events.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Event</a>
                    <a href="{{ route('admin.statistics.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Statistik</a>
                @elseif (auth()->user()->role?->value === 'superadmin')
                    <a href="{{ route('superadmin.verifications.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Semua verifikasi</a>
                    <a href="{{ route('superadmin.audit-logs.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Audit log</a>
                    <a href="{{ route('superadmin.traditions.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Agama</a>
                    <a href="{{ route('superadmin.admins.index') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Admin agama</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="rounded px-2 py-2 text-sm font-medium hover:underline">Profil</a>
                <form method="POST" action="{{ route('logout') }}">@csrf <button class="rounded px-2 py-2 text-left text-sm font-medium hover:underline">Keluar</button></form>
            </div>
        </div>
    </div>
</nav>
