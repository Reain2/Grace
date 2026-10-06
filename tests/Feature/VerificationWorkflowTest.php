<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\Tradition;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_claim_and_approve_only_own_tradition(): void
    {
        $islam = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $hindu = Tradition::create(['name' => 'Hindu', 'slug' => 'hindu', 'is_active' => true]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $islam->id]);
        $otherAdmin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $hindu->id]);
        $user = User::factory()->create(['tradition_id' => $islam->id, 'verification_status' => VerificationStatus::Pending]);
        $verification = VerificationRequest::create([
            'user_id' => $user->id,
            'tradition_id' => $islam->id,
            'proof_type' => 'dummy_image',
            'proof_path' => 'verification-proofs/dummy.png',
        ]);

        $this->actingAs($otherAdmin)->post(route('admin.verifications.claim', $verification))->assertForbidden();
        $this->actingAs($admin)->post(route('admin.verifications.claim', $verification))->assertSessionHas('status');
        $this->actingAs($admin)->post(route('admin.verifications.approve', $verification))->assertSessionHas('status');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'verification_status' => VerificationStatus::Approved->value]);
        $this->assertDatabaseHas('verification_requests', ['id' => $verification->id, 'status' => VerificationStatus::Approved->value]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'verification.approve', 'target_id' => $verification->id]);
        $this->assertDatabaseHas('notifications', ['notifiable_id' => $user->id, 'type' => 'App\\Notifications\\GraceDatabaseNotification']);
    }

    public function test_rejection_escalates_after_three_attempts(): void
    {
        $tradition = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $tradition->id]);
        $user = User::factory()->create(['tradition_id' => $tradition->id]);
        $verification = VerificationRequest::create([
            'user_id' => $user->id,
            'tradition_id' => $tradition->id,
            'proof_type' => 'dummy_image',
            'proof_path' => 'verification-proofs/dummy.png',
            'status' => VerificationStatus::InReview,
            'claimed_by' => $admin->id,
        ]);

        $this->actingAs($admin)->post(route('admin.verifications.reject', $verification), ['reason' => 'Bukti dummy belum sesuai.']);

        $this->assertDatabaseHas('verification_requests', ['id' => $verification->id, 'status' => VerificationStatus::Rejected->value, 'attempts' => 1]);
    }
}
