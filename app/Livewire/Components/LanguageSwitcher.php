<?php

namespace App\Livewire\Components;

use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $currentLocale;

    public function mount(): void
    {
        $this->currentLocale = app()->getLocale();
    }

    public function switchLocale(string $locale): void
    {
        if (in_array($locale, ['en', 'km'])) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
            $this->currentLocale = $locale;
            $this->dispatch('locale-changed', locale: $locale);
        }
    }

    public function render()
    {
        return view('livewire.components.language-switcher');
    }
}
