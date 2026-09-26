<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColorSchemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_pins_the_document_to_a_light_colour_scheme(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<meta name="color-scheme" content="light">', false);
        $response->assertSee('<meta name="theme-color" content="#EDE5D8">', false);
    }

    public function test_storefront_header_uses_the_dark_obsidian_panel(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        $header = $this->headerClasses($response->getContent());

        $this->assertStringContainsString('glass-panel-dark', $header);
        $this->assertStringNotContainsString('glass-panel ', $header);
        $this->assertStringNotContainsString('bg-[#F8F5EF]', $header);
        $this->assertStringNotContainsString('border-[#D8C9B8]', $header);
    }

    public function test_admin_pages_pin_the_document_to_a_light_colour_scheme(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSee('<meta name="color-scheme" content="light">', false);
    }

    private function headerClasses(string $html): string
    {
        $this->assertSame(1, preg_match('/<header[^>]*>/', $html, $matches), 'Expected exactly one <header> element.');

        return $matches[0];
    }
}
