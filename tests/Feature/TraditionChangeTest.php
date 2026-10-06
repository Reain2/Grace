<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TraditionChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_changing_tradition_requires_reverification(): void
    {
        Storage::fake('local');
        $old = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $new = Tradition::create(['name' => 'Hindu', 'slug' => 'hindu', 'is_active' => true]);
        $user = User::factory()->create(['role' => Role::User, 'tradition_id' => $old->id, 'verification_status' => VerificationStatus::Approved]);

        $this->actingAs($user)->post(route('profile.tradition.change'), ['tradition_id' => $new->id, 'proof_file' => UploadedFile::fake()->image('dummy.png')])->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'tradition_id' => $new->id, 'verification_status' => VerificationStatus::Pending->value]);
        $this->assertDatabaseHas('verification_requests', ['user_id' => $user->id, 'tradition_id' => $new->id, 'status' => VerificationStatus::Pending->value]);
    }
}
