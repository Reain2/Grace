<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\Quote;
use App\Models\ReadingPlan;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_only_sees_content_from_own_tradition(): void
    {
        $islam = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $hindu = Tradition::create(['name' => 'Hindu', 'slug' => 'hindu', 'is_active' => true]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $islam->id]);
        $user = User::factory()->create(['role' => Role::User, 'tradition_id' => $islam->id, 'verification_status' => VerificationStatus::Approved]);
        Quote::create(['tradition_id' => $islam->id, 'body' => 'Islam quote', 'source' => 'Demo', 'created_by' => $admin->id]);
        Quote::create(['tradition_id' => $hindu->id, 'body' => 'Hindu quote', 'source' => 'Demo', 'created_by' => $admin->id]);
        ReadingPlan::create(['tradition_id' => $islam->id, 'title' => 'Islam plan', 'total_days' => 1, 'source' => 'Demo', 'created_by' => $admin->id]);
        ReadingPlan::create(['tradition_id' => $hindu->id, 'title' => 'Hindu plan', 'total_days' => 1, 'source' => 'Demo', 'created_by' => $admin->id]);

        $this->actingAs($user)->get(route('user.quotes.index'))->assertSee('Islam quote')->assertDontSee('Hindu quote');
        $this->actingAs($user)->get(route('user.reading-plans.index'))->assertSee('Islam plan')->assertDontSee('Hindu plan');
    }
}
