<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\JournalEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JournalTest extends TestCase
{
    use RefreshDatabase;

    public function test_journal_is_private_to_owner(): void
    {
        $owner = User::factory()->create(['role' => Role::User, 'verification_status' => VerificationStatus::Approved]);
        $other = User::factory()->create(['role' => Role::User, 'verification_status' => VerificationStatus::Approved]);
        $entry = JournalEntry::create(['user_id' => $owner->id, 'entry_date' => today(), 'title' => 'Pribadi', 'body' => 'Refleksi']);
        $this->actingAs($owner)->get(route('user.journal.index'))->assertOk()->assertSee('Pribadi');
        $this->actingAs($other)->patch(route('user.journal.update', $entry), ['entry_date' => today()->toDateString(), 'title' => 'X', 'body' => 'X'])->assertForbidden();
    }
}
