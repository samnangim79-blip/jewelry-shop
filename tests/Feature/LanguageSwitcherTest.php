<?php

namespace Tests\Feature;

use App\Livewire\Components\LanguageSwitcher;
use Livewire\Livewire;
use Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    public function test_switch_to_khmer_persists_session_locale_and_redirects_via_wire_navigate(): void
    {
        Livewire::test(LanguageSwitcher::class)
            ->assertSet('currentLocale', 'en')
            ->call('switchLocale', 'km')
            ->assertSet('currentLocale', 'km')
            ->assertRedirect();

        $this->assertSame('km', session('locale'));
        $this->assertSame('km', app()->getLocale());
    }

    public function test_switch_back_to_english_persists_locale_and_redirects(): void
    {
        session()->put('locale', 'km');
        app()->setLocale('km');

        Livewire::test(LanguageSwitcher::class)
            ->assertSet('currentLocale', 'km')
            ->call('switchLocale', 'en')
            ->assertSet('currentLocale', 'en')
            ->assertRedirect();

        $this->assertSame('en', session('locale'));
        $this->assertSame('en', app()->getLocale());
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
