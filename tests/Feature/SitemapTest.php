<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_valid_xml(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('xml', strtolower((string) $response->headers->get('Content-Type')));

        $content = $response->getContent();

        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', trim($content));
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', $content);
        $this->assertStringContainsString('https://soziecollection.twinasafaris.com/', $content);
        $this->assertStringContainsString('https://soziecollection.twinasafaris.com/shop', $content);
        $this->assertStringContainsString('https://soziecollection.twinasafaris.com/track-order', $content);

        // Ensure private / admin / auth routes are excluded
        $this->assertStringNotContainsString('/admin', $content);
        $this->assertStringNotContainsString('/login', $content);
        $this->assertStringNotContainsString('/register', $content);
        $this->assertStringNotContainsString('/cart', $content);
        $this->assertStringNotContainsString('/checkout', $content);
        $this->assertStringNotContainsString('/account', $content);

        // Validate XML schema parsing
        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, 'Sitemap content is not valid XML');
    }

    public function test_robots_txt_returns_correct_content(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/plain', (string) $response->headers->get('Content-Type'));

        $content = $response->getContent();

        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Allow: /', $content);
        $this->assertStringContainsString('Disallow: /admin', $content);
        $this->assertStringContainsString('Sitemap: https://soziecollection.twinasafaris.com/sitemap.xml', $content);
    }

    public function test_artisan_sitemap_generate_command(): void
    {
        $this->artisan('sitemap:generate')
            ->assertExitCode(0);

        $this->assertFileExists(public_path('sitemap.xml'));
        $this->assertFileExists(public_path('robots.txt'));

        $sitemapContent = file_get_contents(public_path('sitemap.xml'));
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', trim($sitemapContent));

        $robotsContent = file_get_contents(public_path('robots.txt'));
        $this->assertStringContainsString('Sitemap: https://soziecollection.twinasafaris.com/sitemap.xml', $robotsContent);
    }
}
