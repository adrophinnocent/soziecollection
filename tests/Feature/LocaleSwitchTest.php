<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    use RefreshDatabase;

    public function test_switching_to_an_unsupported_locale_returns_not_found(): void
    {
        $this->get('/lang/fr')->assertNotFound();
    }

    public function test_locale_switch_is_case_insensitive(): void
    {
        $this->get('/lang/EN')->assertRedirect();

        $this->assertSame('en', session('locale'));
    }

    public function test_unsupported_session_locale_falls_back_to_the_default(): void
    {
        $response = $this->withSession(['locale' => 'fr'])->get('/');

        $response->assertOk();
        $response->assertSee('lang="en"', false);
    }

    public function test_html_lang_attribute_follows_the_active_locale(): void
    {
        $this->get('/lang/sw');

        $this->get('/')->assertSee('lang="sw"', false);

        $this->get('/lang/en');

        $this->get('/')->assertSee('lang="en"', false);
    }

    public function test_locale_switch_without_a_referer_returns_home(): void
    {
        $this->get('/lang/sw', ['Referer' => null])->assertRedirect(route('home'));
    }

    public function test_order_status_label_is_translated_and_never_leaks_a_key(): void
    {
        $order = (new Order)->forceFill(['status' => 'shipped']);

        App::setLocale('en');
        $this->assertSame('Shipped', $order->status_label);

        App::setLocale('sw');
        $this->assertSame('Imetumwa', $order->status_label);
        $this->assertStringNotContainsString('order.status', $order->status_label);
    }

    public function test_role_label_is_translated_once(): void
    {
        $user = (new User)->forceFill(['role' => User::ROLE_SUPER_ADMIN]);

        App::setLocale('en');
        $this->assertSame('Super Admin', $user->role_label);

        App::setLocale('sw');
        $this->assertSame('Msimamizi Mkuu', $user->role_label);
    }

    public function test_storefront_flash_messages_follow_the_active_locale(): void
    {
        $this->withSession(['locale' => 'en'])
            ->get('/checkout')
            ->assertRedirect(route('shop.index'))
            ->assertSessionHas('info', 'Your cart is empty. Please choose a fragrance first.');

        $this->withSession(['locale' => 'sw'])
            ->get('/checkout')
            ->assertRedirect(route('shop.index'))
            ->assertSessionHas('info', 'Kikapu chako ni tupu. Tafadhali chagua harufu kwanza.');
    }

    public function test_admin_pages_stay_in_english_even_with_a_swahili_session(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->withSession(['locale' => 'sw'])->get('/admin');

        $response->assertOk();
        $response->assertSee('lang="en"', false);
    }

    public function test_validation_messages_are_localised(): void
    {
        $response = $this->from('/login')->withSession(['locale' => 'sw'])->post('/login', []);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'email' => 'Anwani ya barua pepe inahitajika.',
            'password' => 'Nenosiri linahitajika.',
        ]);
    }
}
