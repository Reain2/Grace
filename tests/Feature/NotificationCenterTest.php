<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_and_read_notification(): void
    {
        $user = User::factory()->create(['role' => Role::User, 'verification_status' => VerificationStatus::Approved]);
        $notification = new DatabaseNotification(['id' => '11111111-1111-1111-1111-111111111111', 'type' => 'demo', 'data' => ['title' => 'Demo', 'message' => 'Pesan demo']]);
        $user->notifications()->save($notification);
        $this->actingAs($user)->get(route('user.notifications.index'))->assertOk()->assertSee('Pesan demo');
        $this->actingAs($user)->post(route('user.notifications.read', $notification->id))->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);
    }
}
