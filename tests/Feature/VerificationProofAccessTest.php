<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Tradition;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VerificationProofAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_matching_admin_can_view_proof(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('verification-proofs/dummy.png', 'proof');
        $islam = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $hindu = Tradition::create(['name' => 'Hindu', 'slug' => 'hindu', 'is_active' => true]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $islam->id]);
        $otherAdmin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $hindu->id]);
        $user = User::factory()->create(['tradition_id' => $islam->id]);
        $verification = VerificationRequest::create(['user_id' => $user->id, 'tradition_id' => $islam->id, 'proof_type' => 'dummy_image', 'proof_path' => 'verification-proofs/dummy.png']);

        $this->actingAs($otherAdmin)->get(route('admin.verifications.proof', $verification))->assertForbidden();
        $response = $this->actingAs($admin)->get(route('admin.verifications.proof', $verification));
        $response->assertOk()->assertHeader('Cache-Control', 'no-store, private')->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}
