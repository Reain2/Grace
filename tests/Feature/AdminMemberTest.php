<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\UserStatus;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_toggle_only_member_from_own_tradition(): void
    {
        $islam = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $hindu = Tradition::create(['name' => 'Hindu', 'slug' => 'hindu', 'is_active' => true]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $islam->id]);
        $member = User::factory()->create(['role' => Role::User, 'tradition_id' => $islam->id]);
        $otherMember = User::factory()->create(['role' => Role::User, 'tradition_id' => $hindu->id]);

        $this->actingAs($admin)->post(route('admin.members.toggle', $member))->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $member->id, 'status' => UserStatus::Suspended->value]);
        $this->actingAs($admin)->post(route('admin.members.toggle', $otherMember))->assertForbidden();
    }
}
