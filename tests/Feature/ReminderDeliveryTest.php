<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\Reminder;
use App\Models\User;
use App\Notifications\GraceDatabaseNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReminderDeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_channel_is_available_for_reminders(): void
    {
        $notification = new GraceDatabaseNotification('Judul', 'Pesan', true);
        $this->assertSame(['database', 'mail'], $notification->via(User::factory()->make()));
    }

    public function test_due_reminder_is_delivered_once_per_slot(): void
    {
        $user = User::factory()->create(['role' => Role::User, 'verification_status' => VerificationStatus::Approved, 'timezone' => 'Asia/Jakarta']);
        $now = Carbon::now('Asia/Jakarta');
        $reminder = Reminder::create(['user_id' => $user->id, 'label' => 'Demo', 'remind_at' => $now->format('H:i'), 'days' => [$now->dayOfWeekIso], 'channel' => 'web', 'timezone' => 'Asia/Jakarta', 'is_active' => true]);
        $this->artisan('reminders:send', ['--at' => $now->format('H:i')])->assertSuccessful();
        $this->artisan('reminders:send', ['--at' => $now->format('H:i')])->assertSuccessful();
        $this->assertDatabaseCount('reminder_deliveries', 1);
        $this->assertDatabaseHas('notifications', ['notifiable_id' => $user->id]);
    }
}
