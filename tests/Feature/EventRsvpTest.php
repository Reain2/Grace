<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\Event;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventRsvpTest extends TestCase
{
    use RefreshDatabase;

    public function test_quota_full_event_waitlists_user(): void
    {
        $tradition = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $tradition->id]);
        $one = User::factory()->create(['role' => Role::User, 'tradition_id' => $tradition->id, 'verification_status' => VerificationStatus::Approved]);
        $two = User::factory()->create(['role' => Role::User, 'tradition_id' => $tradition->id, 'verification_status' => VerificationStatus::Approved]);
        $event = Event::create(['tradition_id' => $tradition->id, 'title' => 'Demo', 'description' => 'Demo', 'starts_at' => now()->addDay(), 'ends_at' => now()->addDay()->addHour(), 'quota' => 1, 'status' => 'published', 'created_by' => $admin->id]);
        $this->actingAs($one)->post(route('user.events.rsvp', $event));
        $this->actingAs($two)->post(route('user.events.rsvp', $event));
        $this->assertDatabaseHas('event_rsvps', ['event_id' => $event->id, 'user_id' => $two->id, 'status' => 'waitlisted']);
    }
}
