<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    protected function fakeHub(array $extra = []): void
    {
        Http::fake(array_merge([
            '*/api/v1/content*' => Http::response([
                'site' => [
                    'slug' => 'arab',
                    'name' => 'ARAB Promo',
                    'settings' => config('site.settings'),
                ],
                'sections' => [],
            ], 200),
            '*/api/v1/leads*' => Http::response(['status' => 'accepted'], 201),
            '*/api/v1/events*' => Http::response(['status' => 'accepted'], 201),
        ], $extra));
    }
}
