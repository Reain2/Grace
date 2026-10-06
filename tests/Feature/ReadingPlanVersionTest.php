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

class ReadingPlanVersionTest extends TestCase
{
    use RefreshDatabase;

    public function test_enrollment_keeps_plan_version_when_admin_updates_plan(): void
    {
        $tradition = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $user = User::factory()->create(['role' => Role::User, 'tradition_id' => $tradition->id, 'verification_status' => VerificationStatus::Approved]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $tradition->id]);
        $plan = ReadingPlan::create(['tradition_id' => $tradition->id, 'title' => 'Lama', 'total_days' => 1, 'source' => 'Demo', 'created_by' => $admin->id]);

        $enrollment = app(ReadingProgressService::class)->start($user, $plan);
        $this->actingAs($admin)->patch(route('admin.reading-plans.update', $plan), ['title' => 'Baru', 'source' => 'Demo']);

        $this->assertSame(1, $enrollment->fresh()->plan_version);
        $this->assertSame(2, $plan->fresh()->version);
    }
}
