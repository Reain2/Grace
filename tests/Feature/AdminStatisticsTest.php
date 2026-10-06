<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_only_own_tradition_statistics(): void
    {
        $tradition = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $tradition->id]);
        $this->actingAs($admin)->get(route('admin.statistics.index'))->assertOk()->assertSee('Statistik Islam');
    }
}
