<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\Tradition;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VerificationResubmitTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejected_user_can_resubmit_dummy_proof(): void
    {
        Storage::fake('local');
        $tradition = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $user = User::factory()->create(['role' => Role::User, 'tradition_id' => $tradition->id, 'verification_status' => VerificationStatus::Rejected]);
        $request = VerificationRequest::create([
            'user_id' => $user->id,
            'tradition_id' => $tradition->id,
            'proof_type' => 'dummy_image',
            'proof_path' => 'verification-proofs/old.png',
            'status' => VerificationStatus::Rejected,
            'attempts' => 1,
            'reject_reason' => 'Bukti dummy belum jelas.',
        ]);

        $this->actingAs($user)->post(route('verification.resubmit'), ['proof_file' => UploadedFile::fake()->image('new.png')])->assertRedirect();

        $this->assertDatabaseHas('verification_requests', ['id' => $request->id, 'status' => VerificationStatus::Pending->value, 'attempts' => 1, 'reject_reason' => null]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'verification_status' => VerificationStatus::Pending->value]);
    }
}
