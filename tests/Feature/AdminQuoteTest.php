<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Quote;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminQuoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_own_quote_but_not_other_tradition(): void
    {
        $islam = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $hindu = Tradition::create(['name' => 'Hindu', 'slug' => 'hindu', 'is_active' => true]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $islam->id]);
        $quote = Quote::create(['tradition_id' => $islam->id, 'body' => 'Lama', 'source' => 'Demo', 'created_by' => $admin->id]);
        $other = Quote::create(['tradition_id' => $hindu->id, 'body' => 'Lain', 'source' => 'Demo', 'created_by' => $admin->id]);

        $this->actingAs($admin)->patch(route('admin.quotes.update', $quote), ['body' => 'Baru', 'source' => 'Demo'])->assertRedirect();
        $this->actingAs($admin)->patch(route('admin.quotes.update', $other), ['body' => 'Tidak boleh', 'source' => 'Demo'])->assertForbidden();
        $this->assertDatabaseHas('quotes', ['id' => $quote->id, 'body' => 'Baru']);
    }
}
