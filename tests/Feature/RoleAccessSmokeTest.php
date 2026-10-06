<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_dashboards_and_protected_routes_are_separated(): void
    {
        $tradition = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $user = User::factory()->create(['role' => Role::User, 'tradition_id' => $tradition->id, 'verification_status' => VerificationStatus::Approved]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $tradition->id]);
        $superadmin = User::factory()->create(['role' => Role::Superadmin]);

        $this->actingAs($user)->get(route('user.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($superadmin)->get(route('superadmin.dashboard'))->assertOk();
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($admin)->get(route('superadmin.dashboard'))->assertForbidden();
    }
}
