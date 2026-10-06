<x-guest-layout>
    <div class="mb-8"><p class="heritage-label">Masuk kembali</p><h1 class="mt-2 text-3xl font-semibold">Ruangmu menunggu.</h1><p class="mt-3 text-sm leading-6" style="color: var(--heritage-muted)">Masuk untuk melanjutkan bacaan sesuai Agamamu.</p></div>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div><x-input-label for="email" value="Email" /><x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" /><x-input-error :messages="$errors->get('email')" class="mt-2" /></div>
        <div><x-input-label for="password" value="Kata sandi" /><x-text-input id="password" class="mt-2 block w-full" type="password" name="password" required autocomplete="current-password" /><x-input-error :messages="$errors->get('password')" class="mt-2" /></div>
        <label for="remember_me" class="flex items-center gap-2 text-sm"><input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-400"> Ingat perangkat ini</label>
        <div class="flex flex-wrap items-center justify-between gap-4 pt-2"><a class="text-sm font-semibold underline" href="{{ route('register') }}">Buat akun</a><button class="heritage-button-primary">Masuk</button></div>
        @if (Route::has('password.request'))<a class="block text-sm underline" style="color: var(--heritage-muted)" href="{{ route('password.request') }}">Lupa kata sandi?</a>@endif
    </form>
</x-guest-layout>
