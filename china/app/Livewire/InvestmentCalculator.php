<?php

namespace App\Livewire;

use App\Services\HubClient;
use Livewire\Attributes\Locked;
use Livewire\Component;

class InvestmentCalculator extends Component
{
    public float $amount = 1000;

    public string $currency = 'USD';

    /** @var list<string> */
    #[Locked]
    public array $currencies = [];

    #[Locked]
    public float $presalePriceUsd = 0.05;

    /** @var array<string, float> */
    #[Locked]
    public array $fxRates = [];

    #[Locked]
    public bool $enabled = true;

    #[Locked]
    public bool $hasTrackedUse = false;

    public function mount(HubClient $hub): void
    {
        $settings = $hub->settings();
        $allowed = array_values(array_filter(
            config('site.design.currencies', ['USD', 'CNY']),
            fn ($code) => is_string($code) && $code !== '',
        ));

        $this->currencies = array_values(array_filter(
            $settings['currencies'] ?? $allowed,
            fn ($code) => is_string($code) && in_array($code, $allowed, true),
        ));

        if ($this->currencies === []) {
            $this->currencies = $allowed;
        }

        $price = $settings['presale_price_usd'] ?? config('site.settings.presale_price_usd', 0.05);
        $this->presalePriceUsd = (float) (is_array($price) ? ($price['value'] ?? 0.05) : $price);

        $rates = $settings['fx_rates'] ?? config('site.settings.fx_rates', []);
        $this->fxRates = [];
        foreach ($rates as $code => $rate) {
            $this->fxRates[(string) $code] = (float) $rate;
        }

        $this->enabled = (bool) data_get($settings, 'feature_flags.calculator_enabled', true);

        if (app()->getLocale() === 'zh_CN' && in_array('CNY', $this->currencies, true)) {
            $this->currency = 'CNY';
        } else {
            $this->currency = in_array('USD', $this->currencies, true)
                ? 'USD'
                : ($this->currencies[0] ?? 'USD');
        }
    }

    public function updatedAmount(mixed $value): void
    {
        $this->amount = is_numeric($value) ? max(0, (float) $value) : 0;
        $this->trackUse();
    }

    public function updatedCurrency(string $currency): void
    {
        if (! in_array($currency, $this->currencies, true)) {
            $this->currency = $this->currencies[0] ?? 'USD';

            return;
        }

        $this->trackUse();
    }

    public function getFxRateProperty(): float
    {
        $rate = (float) ($this->fxRates[$this->currency] ?? 1);

        return $rate > 0 ? $rate : 1.0;
    }

    public function getAmountUsdProperty(): float
    {
        return round($this->amount / $this->fxRate, 2);
    }

    public function getAmountCnyProperty(): float
    {
        $rate = (float) ($this->fxRates['CNY'] ?? 1);

        return round($this->amountUsd * ($rate > 0 ? $rate : 1.0), 2);
    }

    public function getTokensProperty(): float
    {
        if ($this->presalePriceUsd <= 0) {
            return 0;
        }

        return round($this->amountUsd / $this->presalePriceUsd, 4);
    }

    public function render()
    {
        return view('livewire.investment-calculator');
    }

    protected function trackUse(): void
    {
        if ($this->hasTrackedUse || ! $this->enabled) {
            return;
        }

        $this->hasTrackedUse = true;

        app(HubClient::class)->trackEvent('calculator_use', [
            'meta' => [
                'amount' => $this->amount,
                'currency' => $this->currency,
                'amount_usd' => $this->amountUsd,
                'tokens' => $this->tokens,
            ],
        ]);
    }
}
