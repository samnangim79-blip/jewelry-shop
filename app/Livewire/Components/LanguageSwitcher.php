<?php

namespace App\Livewire\Components;

use Livewire\Component;

class LanguageSwitcher extends Component
{
    public string $currentLocale;

    public string $returnUrl;

    public function mount(): void
    {
        $this->currentLocale = app()->getLocale();
        $this->returnUrl = request()->url();
    }

    public function switchLocale(string $locale)
    {
        if (! in_array($locale, ['en', 'km'], true)) {
            return null;
        }

        session()->put('locale', $locale);
        app()->setLocale($locale);
        $this->currentLocale = $locale;

        return $this->redirect($this->returnUrl, navigate: true);
    }

    public function render()
    {
        return view('livewire.components.language-switcher');
    }
}
