<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\ReadingPlan;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReadingPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_own_plan_but_not_other_tradition(): void
    {
        $islam = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $hindu = Tradition::create(['name' => 'Hindu', 'slug' => 'hindu', 'is_active' => true]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $islam->id]);
        $plan = ReadingPlan::create(['tradition_id' => $islam->id, 'title' => 'Lama', 'total_days' => 1, 'source' => 'Demo', 'created_by' => $admin->id]);
        $other = ReadingPlan::create(['tradition_id' => $hindu->id, 'title' => 'Lain', 'total_days' => 1, 'source' => 'Demo', 'created_by' => $admin->id]);

        $this->actingAs($admin)->patch(route('admin.reading-plans.update', $plan), ['title' => 'Baru', 'source' => 'Demo'])->assertRedirect();
        $this->actingAs($admin)->delete(route('admin.reading-plans.destroy', $other))->assertForbidden();
        $this->assertDatabaseHas('reading_plans', ['id' => $plan->id, 'title' => 'Baru']);
    }
}
