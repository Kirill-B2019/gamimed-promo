<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->get('/')->assertRedirect('/zh_CN');
    }

    public function test_site_slug_path_redirects_to_default_locale(): void
    {
        $this->get('/china')->assertRedirect('/zh_CN');
    }
}
