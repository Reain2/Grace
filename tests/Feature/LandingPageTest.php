<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_landing_page_has_auth_actions_and_scroll_content(): void
    {
        $this->get('/')->assertOk()->assertSee('Buat ruang pribadi')->assertSee('Masuk ke Grace')->assertSee('Dibuat untuk dipakai perlahan.')->assertSee('Mulai dari langkah kecil');
    }
}
