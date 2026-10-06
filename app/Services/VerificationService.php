<?php

namespace App\Services;

use App\Enums\Role;
use App\Enums\UserStatus;
use App\Enums\VerificationStatus;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Notifications\GraceDatabaseNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class VerificationService
{
    public function streamProof(VerificationRequest $verification, User $reviewer): mixed
    {
        $this->authorizeReviewer($verification, $reviewer);
        abort_unless($verification->proof_path && Storage::disk('local')->exists($verification->proof_path), 404);

        $response = response()->file(Storage::disk('local')->path($verification->proof_path), [
            'X-Content-Type-Options' => 'nosniff',
            'Content-Disposition' => 'inline; filename="demo-proof"',
        ]);
        $response->setPrivate();
        $response->headers->set('Cache-Control', 'no-store, private');
        AuditLog::create(['actor_id' => $reviewer->id, 'action' => 'proof.view', 'target_type' => VerificationRequest::class, 'target_id' => $verification->id]);

        return $response;
    }

    public function claim(VerificationRequest $verification, User $reviewer): VerificationRequest
    {
        return DB::transaction(function () use ($verification, $reviewer) {
            $verification = VerificationRequest::query()->lockForUpdate()->findOrFail($verification->id);
            $this->authorizeReviewer($verification, $reviewer);

            $claimExpired = $verification->status === VerificationStatus::InReview
                && $verification->claimed_at?->lt(now()->subMinutes(30));
            if (! in_array($verification->status, [VerificationStatus::Pending, VerificationStatus::InReview], true) || (! $claimExpired && $verification->status === VerificationStatus::InReview)) {
                throw ValidationException::withMessages(['verification' => 'Request tidak sedang menunggu review.']);
            }

            $verification->update([
                'status' => VerificationStatus::InReview,
                'claimed_by' => $reviewer->id,
                'claimed_at' => now(),
            ]);

            return $verification->refresh();
        });
    }

    public function approve(VerificationRequest $verification, User $reviewer): VerificationRequest
    {
        return DB::transaction(function () use ($verification, $reviewer) {
            $verification = VerificationRequest::query()->lockForUpdate()->findOrFail($verification->id);
            $this->authorizeReviewer($verification, $reviewer);
            $this->ensureReviewable($verification);

            $verification->update([
                'status' => VerificationStatus::Approved,
                'is_active' => false,
                'decided_by' => $reviewer->id,
                'decided_at' => now(),
                'proof_delete_at' => now()->addDays(7),
            ]);
            $verification->user()->update([
                'verification_status' => VerificationStatus::Approved,
                'status' => UserStatus::Active,
            ]);
            AuditLog::create(['actor_id' => $reviewer->id, 'action' => 'verification.approve', 'target_type' => VerificationRequest::class, 'target_id' => $verification->id]);
            $verification->user->notify(new GraceDatabaseNotification('Verifikasi disetujui', 'Akunmu sudah dapat memakai fitur interaktif Grace.'));

            return $verification->refresh();
        });
    }

    public function resubmit(User $user, UploadedFile $proof): VerificationRequest
    {
        return DB::transaction(function () use ($user, $proof) {
            $verification = $user->verificationRequests()->latest()->lockForUpdate()->firstOrFail();
            abort_unless($verification->status === VerificationStatus::Rejected, 422);
            abort_if($verification->attempts >= 3, 422, 'Batas percobaan tercapai.');

            $path = Storage::disk('local')->putFile('verification-proofs', $proof);
            if ($verification->proof_path) {
                Storage::disk('local')->delete($verification->proof_path);
            }
            $verification->update([
                'proof_path' => $path,
                'status' => VerificationStatus::Pending,
                'claimed_by' => null,
                'claimed_at' => null,
                'decided_by' => null,
                'decided_at' => null,
                'reject_reason' => null,
                'proof_delete_at' => null,
                'proof_deleted_at' => null,
            ]);
            $user->update(['verification_status' => VerificationStatus::Pending]);

            return $verification->refresh();
        });
    }

    public function reject(VerificationRequest $verification, User $reviewer, string $reason): VerificationRequest
    {
        return DB::transaction(function () use ($verification, $reviewer, $reason) {
            $verification = VerificationRequest::query()->lockForUpdate()->findOrFail($verification->id);
            $this->authorizeReviewer($verification, $reviewer);
            $this->ensureReviewable($verification);
            $attempts = $verification->attempts + 1;
            $status = $attempts >= 3 ? VerificationStatus::NeedsSuperadmin : VerificationStatus::Rejected;

            $verification->update([
                'status' => $status,
                'attempts' => $attempts,
                'decided_by' => $reviewer->id,
                'decided_at' => now(),
                'reject_reason' => $reason,
                'proof_delete_at' => now()->addDays(7),
            ]);
            $verification->user()->update(['verification_status' => $status]);
            AuditLog::create(['actor_id' => $reviewer->id, 'action' => 'verification.reject', 'target_type' => VerificationRequest::class, 'target_id' => $verification->id, 'meta' => ['attempts' => $attempts]]);
            $verification->user->notify(new GraceDatabaseNotification('Verifikasi perlu diperbarui', $reason));

            return $verification->refresh();
        });
    }

    private function ensureReviewable(VerificationRequest $verification): void
    {
        if (! in_array($verification->status, [VerificationStatus::InReview, VerificationStatus::NeedsSuperadmin], true)) {
            throw ValidationException::withMessages(['verification' => 'Request belum diklaim atau sudah diputuskan.']);
        }
    }

    private function authorizeReviewer(VerificationRequest $verification, User $reviewer): void
    {
        abort_unless($reviewer->role === Role::Superadmin || $reviewer->tradition_id === $verification->tradition_id, 403);
        if ($verification->status === VerificationStatus::NeedsSuperadmin) {
            abort_unless($reviewer->role === Role::Superadmin, 403);
        }
    }
}
