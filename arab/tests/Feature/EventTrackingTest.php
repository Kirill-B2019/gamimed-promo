<?php

namespace Tests\Feature;

use App\Jobs\RetryHubEventJob;
use App\Services\HubClient;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EventTrackingTest extends TestCase
{
    public function test_home_queues_a_page_view(): void
    {
        Queue::fake();
        $this->fakeHub();

        $this->get('/en')->assertOk();

        Queue::assertPushed(RetryHubEventJob::class, function (RetryHubEventJob $job) {
            return ($job->payload['event_type'] ?? null) === 'page_view'
                && ($job->payload['path'] ?? null) === '/en'
                && ($job->payload['locale'] ?? null) === 'en';
        });
    }

    public function test_track_endpoint_queues_cta_click(): void
    {
        Queue::fake();
        $this->fakeHub();

        $this->postJson('/track', [
            'event_type' => 'cta_click',
            'path' => '/ar',
            'locale' => 'ar',
            'meta' => ['target' => 'calculator'],
        ])->assertOk()->assertJson(['ok' => true]);

        Queue::assertPushed(RetryHubEventJob::class, function (RetryHubEventJob $job) {
            return ($job->payload['event_type'] ?? null) === 'cta_click'
                && ($job->payload['path'] ?? null) === '/ar'
                && ($job->payload['locale'] ?? null) === 'ar'
                && ($job->payload['meta']['target'] ?? null) === 'calculator';
        });
    }

    public function test_track_endpoint_queues_whitepaper_download(): void
    {
        Queue::fake();
        $this->fakeHub();

        $this->postJson('/track', [
            'event_type' => 'whitepaper_download',
            'path' => '/ar',
        ])->assertOk();

        Queue::assertPushed(RetryHubEventJob::class, function (RetryHubEventJob $job) {
            return ($job->payload['event_type'] ?? null) === 'whitepaper_download';
        });
    }

    public function test_track_endpoint_rejects_unknown_event_types(): void
    {
        Queue::fake();

        $this->postJson('/track', [
            'event_type' => 'password_reset',
        ])->assertUnprocessable();

        Queue::assertNotPushed(RetryHubEventJob::class);
    }

    public function test_allowed_event_types_match_hub_contract(): void
    {
        $this->assertSame(
            ['page_view', 'cta_click', 'calculator_use', 'contact_submit', 'whitepaper_download'],
            HubClient::EVENT_TYPES,
        );
    }
}
