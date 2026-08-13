<?php

namespace App\Livewire;

use Livewire\Component;

class LocaleSwitcher extends Component
{
    public string $locale;

    /** @var list<string> */
    public array $locales = [];

    public function mount(): void
    {
        $this->locales = config('site.locales', ['ar', 'en']);
        $this->locale = app()->getLocale();
    }

    public function switch(string $locale): void
    {
        if (! in_array($locale, $this->locales, true)) {
            return;
        }

        $this->locale = $locale;
        session(['locale' => $locale]);
        cookie()->queue(cookie('locale', $locale, 60 * 24 * 365));

        $this->redirect(route('home', ['locale' => $locale]), navigate: false);
    }

    public function render()
    {
        return view('livewire.locale-switcher');
    }
}
