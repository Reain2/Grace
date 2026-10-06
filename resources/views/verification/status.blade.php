<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Status verifikasi</h2>
    </x-slot>

    <div class="heritage-container py-10">
        <div class="heritage-surface max-w-3xl p-6 sm:p-8">
            @if (session('status')) <p class="mb-5 border border-green-700 bg-green-50 p-3 text-sm text-green-900">{{ session('status') }}</p> @endif
            <p class="heritage-label">{{ auth()->user()->tradition?->name }}</p>
            <p class="mt-3 text-2xl font-semibold">{{ str_replace('_', ' ', ucfirst(auth()->user()->verification_status->value)) }}</p>
            @if ($verification?->reject_reason)
                <p class="mt-5 border border-red-700 bg-red-50 p-4 text-sm text-red-900">Alasan penolakan: {{ $verification->reject_reason }}</p>
            @endif
            <p class="mt-5 leading-7" style="color: var(--heritage-muted)">Halaman dapat dilihat. Interaksi dibuka setelah admin menyetujui verifikasi.</p>
            @if ($verification?->status?->value === 'rejected' && $verification->attempts < 3)
                <form method="POST" action="{{ route('verification.resubmit') }}" enctype="multipart/form-data" class="mt-8 border-t pt-6" style="border-color: var(--heritage-border)">
                    @csrf
                    <label for="proof_file" class="font-semibold">Kirim bukti dummy baru</label>
                    <p class="mt-1 text-sm" style="color: var(--heritage-muted)">Gunakan foto dummy. Jangan upload KTP asli.</p>
                    <input id="proof_file" name="proof_file" type="file" accept="image/jpeg,image/png" required class="mt-4 block w-full text-sm">
                    <x-input-error :messages="$errors->get('proof_file')" class="mt-2" />
                    <button class="heritage-button-primary mt-5">Kirim ulang</button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
