<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\ReadingPlan;
use App\Models\Tradition;
use App\Models\User;
use App\Services\ReadingProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReadingProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_start_plan_check_item_and_get_progress(): void
    {
        $tradition = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $user = User::factory()->create(['role' => Role::User, 'tradition_id' => $tradition->id, 'verification_status' => VerificationStatus::Approved]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $tradition->id]);
        $plan = ReadingPlan::create(['tradition_id' => $tradition->id, 'title' => 'Demo', 'total_days' => 2, 'source' => 'Demo', 'created_by' => $admin->id]);
        $plan->items()->createMany([['day_number' => 1, 'title' => 'Satu', 'reference' => 'Ref 1'], ['day_number' => 2, 'title' => 'Dua', 'reference' => 'Ref 2']]);

        $service = app(ReadingProgressService::class);
        $enrollment = $service->start($user, $plan);
        $service->check($user, $enrollment, $plan->items()->first()->id);

        $this->assertSame(50, $service->percentage($enrollment->fresh()));
        $this->assertSame(1, $service->streak($enrollment->fresh()));
    }

    public function test_streak_allows_one_rest_day(): void
    {
        $tradition = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $user = User::factory()->create(['role' => Role::User, 'tradition_id' => $tradition->id, 'verification_status' => VerificationStatus::Approved]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $tradition->id]);
        $plan = ReadingPlan::create(['tradition_id' => $tradition->id, 'title' => 'Demo', 'total_days' => 2, 'source' => 'Demo', 'created_by' => $admin->id]);
        $items = $plan->items()->createMany([
            ['day_number' => 1, 'title' => 'Satu', 'reference' => 'Ref 1'],
            ['day_number' => 2, 'title' => 'Dua', 'reference' => 'Ref 2'],
        ]);
        $enrollment = app(ReadingProgressService::class)->start($user, $plan);

        $enrollment->progress()->create(['reading_plan_item_id' => $items[0]->id, 'checked_on' => Carbon::today()]);
        $enrollment->progress()->create(['reading_plan_item_id' => $items[1]->id, 'checked_on' => Carbon::today()->subDays(2)]);

        $this->assertSame(2, app(ReadingProgressService::class)->streak($enrollment->fresh()));
    }
}
