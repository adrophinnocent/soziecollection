<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicStorageTest extends TestCase
{
    public function test_serves_files_from_the_public_disk(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/test-image.jpg', 'image-content');

        $response = $this->get('/storage/products/test-image.jpg');

        $response->assertOk();
        $this->assertSame('image-content', $response->streamedContent());
    }

    public function test_returns_not_found_for_a_missing_public_file(): void
    {
        Storage::fake('public');

        $this->get('/storage/products/missing-image.jpg')
            ->assertNotFound();
    }

    public function test_does_not_expose_files_from_the_private_disk(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('private-document.txt', 'private-content');

        $this->get('/storage/private-document.txt')
            ->assertNotFound();
    }
}
