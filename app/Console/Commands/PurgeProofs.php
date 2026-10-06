<?php

namespace App\Console\Commands;

use App\Models\VerificationRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeProofs extends Command
{
    protected $signature = 'proofs:purge';

    protected $description = 'Hapus proof dummy yang sudah melewati masa simpan';

    public function handle(): int
    {
        $purged = 0;

        VerificationRequest::query()
            ->whereNotNull('proof_delete_at')
            ->where('proof_delete_at', '<=', now())
            ->whereNull('proof_deleted_at')
            ->eachById(function (VerificationRequest $verification) use (&$purged): void {
                if ($verification->proof_path) {
                    Storage::disk('local')->delete($verification->proof_path);
                }

                $verification->update([
                    'proof_path' => null,
                    'proof_deleted_at' => now(),
                ]);
                $purged++;
            });

        $this->info("Proof purged: {$purged}");

        return self::SUCCESS;
    }
}
