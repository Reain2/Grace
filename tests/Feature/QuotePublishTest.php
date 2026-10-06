<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Quote;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotePublishTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_own_quote_but_not_other_tradition(): void
    {
        $islam = Tradition::create(['name' => 'Islam', 'slug' => 'islam', 'is_active' => true]);
        $hindu = Tradition::create(['name' => 'Hindu', 'slug' => 'hindu', 'is_active' => true]);
        $admin = User::factory()->create(['role' => Role::ReligionAdmin, 'tradition_id' => $islam->id]);
        $quote = Quote::create(['tradition_id' => $islam->id, 'body' => 'Demo', 'source' => 'Demo', 'is_active' => false, 'created_by' => $admin->id]);
        $other = Quote::create(['tradition_id' => $hindu->id, 'body' => 'Other', 'source' => 'Demo', 'is_active' => false, 'created_by' => $admin->id]);

        $this->actingAs($admin)->post(route('admin.quotes.publish', $quote))->assertRedirect();
        $this->actingAs($admin)->post(route('admin.quotes.publish', $other))->assertForbidden();
        $this->assertDatabaseHas('quotes', ['id' => $quote->id, 'is_active' => true]);
    }
}
