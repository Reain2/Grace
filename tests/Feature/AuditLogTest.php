<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_superadmin_can_view_audit_logs(): void
    {
        $superadmin = User::factory()->create(['role' => Role::Superadmin]);
        $user = User::factory()->create(['role' => Role::User]);
        AuditLog::create(['actor_id' => $superadmin->id, 'action' => 'test.action', 'target_type' => User::class, 'target_id' => $user->id]);

        $this->actingAs($user)->get(route('superadmin.audit-logs.index'))->assertForbidden();
        $this->actingAs($superadmin)->get(route('superadmin.audit-logs.index'))->assertOk()->assertSee('test.action');
    }
}
