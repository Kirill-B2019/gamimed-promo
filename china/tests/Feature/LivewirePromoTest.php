<?php

namespace Tests\Feature;

use App\Jobs\RetryHubEventJob;
use App\Jobs\RetryHubLeadJob;
use App\Livewire\ContactForm;
use App\Livewire\InvestmentCalculator;
use App\Livewire\LocaleSwitcher;
use App\Services\HubClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class LivewirePromoTest extends TestCase
{
    public function test_calculator_converts_local_amount_using_hub_fx_and_price(): void
    {
        Queue::fake();
        $this->fakeHub();

        $component = Livewire::test(InvestmentCalculator::class)
            ->assertSet('currency', 'CNY')
            ->assertSet('currencies', ['USD', 'CNY'])
            ->assertSet('presalePriceUsd', 0.05)
            ->set('amount', 7250)
            ->assertDontSee(__('calculator.disabled'));

        $this->assertSame(1000.0, $component->instance()->amountUsd);
        $this->assertSame(20000.0, $component->instance()->tokens);
        $component->assertSee('CNY');
        $component->assertSee('USD');
        $component->assertDontSee('AED');
    }

    public function test_calculator_shows_cny_equivalent_when_amount_is_usd(): void
    {
        Queue::fake();
        $this->fakeHub();

        $component = Livewire::test(InvestmentCalculator::class)
            ->set('currency', 'USD')
            ->set('amount', 1000);

        $this->assertSame(7250.0, $component->instance()->amountCny);
        $component->assertSee('CNY');
        $component->assertSee(__('calculator.amount_cny'));
    }

    public function test_calculator_ignores_currencies_outside_usd_cny(): void
    {
        Queue::fake();
        $this->fakeHub([
            '*/api/v1/content*' => Http::response([
                'site' => [
                    'slug' => 'china',
                    'settings' => array_replace_recursive(config('site.settings'), [
                        'currencies' => ['USD', 'CNY', 'AED', 'SAR'],
                    ]),
                ],
                'sections' => [],
            ], 200),
        ]);

        Livewire::test(InvestmentCalculator::class)
            ->assertSet('currencies', ['USD', 'CNY'])
            ->assertDontSee('AED');
    }

    public function test_calculator_tracks_use_once_per_visit(): void
    {
        Queue::fake();
        $this->fakeHub();

        Livewire::test(InvestmentCalculator::class)
            ->set('amount', 2000)
            ->set('currency', 'CNY')
            ->set('amount', 3000);

        Queue::assertPushed(RetryHubEventJob::class, 1);
        Queue::assertPushed(RetryHubEventJob::class, function (RetryHubEventJob $job) {
            return ($job->payload['event_type'] ?? null) === 'calculator_use'
                && ($job->payload['meta']['currency'] ?? null) === 'CNY';
        });
    }

    public function test_calculator_respects_disabled_feature_flag(): void
    {
        Queue::fake();
        $this->fakeHub([
            '*/api/v1/content*' => Http::response([
                'site' => [
                    'slug' => 'china',
                    'settings' => array_replace_recursive(config('site.settings'), [
                        'feature_flags' => ['calculator_enabled' => false],
                    ]),
                ],
                'sections' => [],
            ], 200),
        ]);

        Livewire::test(InvestmentCalculator::class)
            ->assertSet('enabled', false)
            ->assertSee(__('calculator.disabled'))
            ->set('amount', 2000);

        Queue::assertNotPushed(RetryHubEventJob::class);
    }

    public function test_contact_form_posts_lead_and_tracks_submit(): void
    {
        Queue::fake();
        $this->fakeHub();

        Livewire::test(ContactForm::class)
            ->set('name', 'Participant')
            ->set('email', 'participant@example.com')
            ->set('phone', '+8613800000000')
            ->set('messenger', 'wechat:gamimed')
            ->set('message', 'Interested in pre-sale')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true)
            ->assertSee(__('contact.thanks'));

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/api/v1/leads')
                && $request['email'] === 'participant@example.com'
                && $request['name'] === 'Participant'
                && $request['messenger'] === 'wechat:gamimed'
                && ($request['meta']['channel'] ?? null) === 'china-contact-form';
        });

        Queue::assertPushed(RetryHubEventJob::class, function (RetryHubEventJob $job) {
            return ($job->payload['event_type'] ?? null) === 'contact_submit';
        });
    }

    public function test_contact_form_requires_at_least_one_channel(): void
    {
        Queue::fake();
        $this->fakeHub();

        Livewire::test(ContactForm::class)
            ->set('name', 'Participant')
            ->call('submit')
            ->assertHasErrors(['email'])
            ->assertSet('submitted', false);

        Http::assertNotSent(fn ($request) => str_contains($request->url(), '/api/v1/leads'));
        Queue::assertNotPushed(RetryHubLeadJob::class);
        Queue::assertNotPushed(RetryHubEventJob::class);
    }

    public function test_contact_form_queues_lead_when_hub_is_down(): void
    {
        Queue::fake();
        $this->fakeHub([
            '*/api/v1/leads*' => Http::response(['message' => 'unavailable'], 503),
        ]);

        Livewire::test(ContactForm::class)
            ->set('email', 'retry@example.com')
            ->call('submit')
            ->assertSet('submitted', true);

        Queue::assertPushed(RetryHubLeadJob::class, function (RetryHubLeadJob $job) {
            return ($job->payload['email'] ?? null) === 'retry@example.com';
        });
    }

    public function test_locale_switcher_redirects_to_selected_locale(): void
    {
        $this->fakeHub();

        Livewire::test(LocaleSwitcher::class)
            ->assertSet('locale', config('app.locale'))
            ->call('switch', 'en')
            ->assertRedirect(route('home', ['locale' => 'en']));
    }

    public function test_locale_switch_route_sets_cookie_and_lands_on_home(): void
    {
        $this->fakeHub();
        Queue::fake();

        $this->get('/locale/en')
            ->assertRedirect(route('home', ['locale' => 'en']))
            ->assertCookie('locale', 'en');
    }

    public function test_unknown_hub_event_type_is_ignored(): void
    {
        Queue::fake();

        $this->assertFalse(app(HubClient::class)->trackEvent('not_a_real_event'));
        Queue::assertNotPushed(RetryHubEventJob::class);
    }
}
