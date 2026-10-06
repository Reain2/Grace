<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\ReadingPlan;
use App\Models\Tradition;
use App\Models\User;
use App\Services\ReadingProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadingPlanSnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function test_enrollment_keeps_immutable_item_snapshot(): void
    {
        $tradition = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $user = User::factory()->create(['role' => Role::User, 'tradition_id' => $tradition->id, 'verification_status' => VerificationStatus::Approved]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $tradition->id]);
        $plan = ReadingPlan::create(['tradition_id' => $tradition->id, 'title' => 'Demo', 'total_days' => 1, 'source' => 'Demo', 'created_by' => $admin->id]);
        $plan->items()->create(['day_number' => 1, 'title' => 'Lama', 'reference' => 'Ref lama']);
        $enrollment = app(ReadingProgressService::class)->start($user, $plan);
        $plan->items()->first()->update(['title' => 'Baru']);

        $this->assertSame('Lama', $enrollment->fresh()->items_snapshot[0]['title']);
    }
}
