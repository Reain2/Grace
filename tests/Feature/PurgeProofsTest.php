<?php

namespace Tests\Feature;

use App\Models\Tradition;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PurgeProofsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_removes_expired_proof(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('verification-proofs/expired.png', 'dummy');
        $tradition = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $user = User::factory()->create(['tradition_id' => $tradition->id]);
        $verification = VerificationRequest::create([
            'user_id' => $user->id,
            'tradition_id' => $tradition->id,
            'proof_type' => 'dummy_image',
            'proof_path' => 'verification-proofs/expired.png',
            'proof_delete_at' => now()->subMinute(),
        ]);

        $this->artisan('proofs:purge')->assertSuccessful();

        Storage::disk('local')->assertMissing('verification-proofs/expired.png');
        $this->assertDatabaseHas('verification_requests', ['id' => $verification->id, 'proof_path' => null]);
    }
}
