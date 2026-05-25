<?php

namespace Tests\Feature;

use App\Livewire\Components\LanguageSwitcher;
use Livewire\Livewire;
use Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    public function test_switch_to_khmer_persists_session_locale_and_redirects_to_return_url(): void
    {
        $returnUrl = url('/admin/companies');

        Livewire::test(LanguageSwitcher::class)
            ->assertSet('currentLocale', 'en')
            ->set('returnUrl', $returnUrl)
            ->call('switchLocale', 'km')
            ->assertSet('currentLocale', 'km')
            ->assertRedirect($returnUrl);

        $this->assertSame('km', session('locale'));
        $this->assertSame('km', app()->getLocale());
    }

    public function test_switch_back_to_english_persists_locale_and_redirects_to_return_url(): void
    {
        session()->put('locale', 'km');
        app()->setLocale('km');

        $returnUrl = url('/admin/users');

        Livewire::test(LanguageSwitcher::class)
            ->assertSet('currentLocale', 'km')
            ->set('returnUrl', $returnUrl)
            ->call('switchLocale', 'en')
            ->assertSet('currentLocale', 'en')
            ->assertRedirect($returnUrl);

        $this->assertSame('en', session('locale'));
        $this->assertSame('en', app()->getLocale());
    }

    public function test_return_url_is_captured_on_mount(): void
    {
        Livewire::test(LanguageSwitcher::class)
            ->assertSet('returnUrl', url()->current());
    }

    public function test_unknown_locale_is_ignored(): void
    {
        Livewire::test(LanguageSwitcher::class)
            ->call('switchLocale', 'fr')
            ->assertSet('currentLocale', 'en')
            ->assertNoRedirect();

        $this->assertNull(session('locale'));
    }
}
