<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_manage_own_reminder(): void
    {
        $user = User::factory()->create(['role' => Role::User, 'verification_status' => VerificationStatus::Approved]);
        $this->actingAs($user)->post(route('user.reminders.store'), ['label' => 'Membaca pagi', 'remind_at' => '07:00', 'days' => [1, 3], 'channel' => 'web', 'timezone' => 'Asia/Jakarta'])->assertRedirect();
        $reminder = Reminder::firstOrFail();
        $this->actingAs($user)->post(route('user.reminders.toggle', $reminder))->assertRedirect();
        $this->assertDatabaseHas('reminders', ['id' => $reminder->id, 'is_active' => false]);
    }
}
