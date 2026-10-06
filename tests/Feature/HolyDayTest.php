<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\HolyDay;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HolyDayTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_only_sees_holy_days_from_own_tradition(): void
    {
        $islam = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $hindu = Tradition::create(['name' => 'Hindu', 'slug' => 'hindu', 'is_active' => true]);
        $user = User::factory()->create(['role' => Role::User, 'tradition_id' => $islam->id, 'verification_status' => VerificationStatus::Approved]);
        HolyDay::create(['tradition_id' => $islam->id, 'name' => 'Islam demo', 'date' => today()->subYear(), 'is_annual' => true]);
        HolyDay::create(['tradition_id' => $hindu->id, 'name' => 'Hindu demo', 'date' => today()->addDay()]);
        $this->actingAs($user)->get(route('user.holy-days.index'))->assertOk()->assertSee('Islam demo')->assertDontSee('Hindu demo');
    }
}
