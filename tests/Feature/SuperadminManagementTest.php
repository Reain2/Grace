<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperadminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_superadmin_can_create_tradition_and_admin(): void
    {
        $superadmin = User::factory()->create(['role' => Role::Superadmin]);
        $user = User::factory()->create(['role' => Role::User]);
        $payload = ['name' => 'Test Faith', 'slug' => 'test-faith'];

        $this->actingAs($user)->post(route('superadmin.traditions.store'), $payload)->assertForbidden();
        $this->actingAs($superadmin)->post(route('superadmin.traditions.store'), $payload)->assertRedirect();
        $tradition = Tradition::where('slug', 'test-faith')->firstOrFail();
        $this->actingAs($superadmin)->post(route('superadmin.admins.store'), ['name' => 'Admin Demo', 'email' => 'new.admin@grace.test', 'tradition_id' => $tradition->id])->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'new.admin@grace.test', 'role' => Role::ReligionAdmin->value]);
    }
}
