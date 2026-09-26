<?php

namespace Tests\Feature\Admin;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomepageSlideControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_open_the_homepage_slide_manager(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->get(route('admin.content'));

        $response->assertOk();
        $response->assertSee('Homepage Hero Slides');
        $response->assertSee('Add New Homepage Slide', false);
    }

    public function test_super_admin_can_create_a_homepage_slide_with_uploaded_design(): void
    {
        Storage::fake('public');
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->post(route('admin.slides.store'), $this->slidePayload([
            'image' => UploadedFile::fake()->image('summer-design.jpg', 1600, 900),
        ]));

        $response->assertRedirect(route('admin.content'));
        $response->assertSessionHas('success');

        $banner = Banner::sole();

        $this->assertModelExists($banner);
        $this->assertSame('Summer Launch', $banner->title);
        $this->assertTrue($banner->is_active);
        $this->assertSame(3, $banner->sort_order);
        Storage::disk('public')->assertExists($banner->image);
    }

    public function test_manager_without_homepage_permission_cannot_create_a_slide(): void
    {
        Storage::fake('public');
        $manager = User::factory()->manager()->create();

        $response = $this->actingAs($manager)->post(route('admin.slides.store'), $this->slidePayload([
            'image' => UploadedFile::fake()->image('design.jpg', 1600, 900),
        ]));

        $response->assertForbidden();
        $this->assertSame(0, Banner::count());
    }

    public function test_create_requires_a_desktop_design(): void
    {
        Storage::fake('public');
        $admin = User::factory()->superAdmin()->create();
        $payload = Arr::except($this->slidePayload(), 'image');

        $response = $this->actingAs($admin)->post(route('admin.slides.store'), $payload);

        $response->assertSessionHasErrors('image');
        $this->assertSame(0, Banner::count());
    }

    public function test_create_rejects_unsafe_button_links(): void
    {
        Storage::fake('public');
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->post(route('admin.slides.store'), $this->slidePayload([
            'image' => UploadedFile::fake()->image('design.jpg', 1600, 900),
            'button_link' => 'javascript:alert(1)',
        ]));

        $response->assertSessionHasErrors('button_link');
        $this->assertSame(0, Banner::count());
    }

    public function test_update_replaces_the_design_and_removes_the_old_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('banners/old-design.jpg', 'old');
        $admin = User::factory()->superAdmin()->create();
        $banner = Banner::create([
            'title' => 'Old Slide',
            'headline' => 'OLD',
            'image' => 'banners/old-design.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.slides.update', $banner), $this->slidePayload([
            'title' => 'Updated Slide',
            'headline' => 'NEW CAMPAIGN',
            'image' => UploadedFile::fake()->image('new-design.jpg', 1920, 1080),
        ]));

        $response->assertRedirect(route('admin.content'));
        $this->assertSame('Updated Slide', $banner->fresh()->title);
        $this->assertSame('NEW CAMPAIGN', $banner->fresh()->headline);
        $this->assertNotSame('banners/old-design.jpg', $banner->fresh()->image);
        Storage::disk('public')->assertMissing('banners/old-design.jpg');
        Storage::disk('public')->assertExists($banner->fresh()->image);
    }

    public function test_delete_removes_the_slide_and_its_design_files(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('banners/design.jpg', 'desktop');
        Storage::disk('public')->put('banners/mobile/design.jpg', 'mobile');
        $admin = User::factory()->superAdmin()->create();
        $banner = Banner::create([
            'title' => 'Delete Me',
            'headline' => 'DELETE',
            'image' => 'banners/design.jpg',
            'mobile_image' => 'banners/mobile/design.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.slides.destroy', $banner));

        $response->assertRedirect(route('admin.content'));
        $this->assertModelMissing($banner);
        Storage::disk('public')->assertMissing('banners/design.jpg');
        Storage::disk('public')->assertMissing('banners/mobile/design.jpg');
    }

    public function test_homepage_renders_only_active_slides(): void
    {
        Storage::fake('public');
        Banner::create([
            'title' => 'Live Slide',
            'eyebrow' => 'LIVE CAMPAIGN',
            'headline' => 'ACTIVE CAMPAIGN HEADLINE',
            'highlight_text' => 'ACTIVE HIGHLIGHT',
            'subtitle' => 'Live description',
            'image' => 'banners/live.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        Banner::create([
            'title' => 'Hidden Slide',
            'headline' => 'HIDDEN CAMPAIGN HEADLINE',
            'image' => 'banners/hidden.jpg',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('ACTIVE CAMPAIGN HEADLINE', false);
        $response->assertDontSee('HIDDEN CAMPAIGN HEADLINE', false);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function slidePayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Summer Launch',
            'eyebrow' => 'NEW ARRIVAL',
            'headline' => 'YOUR NEW SCENT',
            'highlight_text' => 'YOUR SIGNATURE',
            'subtitle' => 'A limited fragrance campaign description.',
            'button_text' => 'SHOP NOW',
            'button_link' => '/shop',
            'secondary_button_text' => 'FIND YOUR SCENT',
            'secondary_button_link' => '/#scent-finder',
            'is_active' => 1,
            'sort_order' => 3,
        ], $overrides);
    }
}
