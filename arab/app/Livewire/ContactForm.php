<?php

namespace App\Livewire;

use App\Services\HubClient;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $messenger = '';

    public string $message = '';

    public bool $submitted = false;

    #[Locked]
    public bool $enabled = true;

    public function mount(HubClient $hub): void
    {
        $settings = $hub->settings();
        $this->enabled = (bool) data_get($settings, 'feature_flags.contact_form', true);
    }

    public function submit(HubClient $hub): void
    {
        if (! $this->enabled) {
            return;
        }

        $key = 'contact-form:'.(request()->ip() ?: 'unknown');

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', __('contact.too_many'));

            return;
        }

        $validated = $this->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'messenger' => ['nullable', 'string', 'max:64'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        if (
            blank($validated['email'])
            && blank($validated['phone'])
            && blank($validated['messenger'])
            && blank($validated['message'])
        ) {
            $this->addError('email', __('contact.at_least_one'));

            return;
        }

        RateLimiter::hit($key, 60);

        $hub->submitLead([
            'name' => $validated['name'] ?: null,
            'email' => $validated['email'] ?: null,
            'phone' => $validated['phone'] ?: null,
            'messenger' => $validated['messenger'] ?: null,
            'message' => $validated['message'] ?: null,
            'meta' => ['channel' => 'arab-contact-form'],
        ]);

        $hub->trackEvent('contact_submit', [
            'meta' => [
                'has_email' => filled($validated['email']),
                'has_phone' => filled($validated['phone']),
                'has_messenger' => filled($validated['messenger']),
            ],
        ]);

        $this->reset(['name', 'email', 'phone', 'messenger', 'message']);
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
