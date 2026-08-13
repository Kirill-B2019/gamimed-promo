<?php

namespace Tests\Unit;

use App\Services\Totp;
use PHPUnit\Framework\TestCase;

class TotpTest extends TestCase
{
    public function test_generated_secret_round_trips_with_a_fixed_timestamp(): void
    {
        $totp = new Totp;
        $secret = $totp->generateSecret();
        $timestamp = 1_700_000_000;
        $code = $totp->current($secret, $timestamp);

        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
        $this->assertTrue($totp->verify($secret, $code, 1, $timestamp));
        $this->assertFalse($totp->verify($secret, '000000', 1, $timestamp));
    }

    public function test_otpauth_uri_contains_issuer_and_secret(): void
    {
        $uri = (new Totp)->otpauthUri('MFRGGZDF', 'admin@gamimed.local');

        $this->assertStringStartsWith('otpauth://totp/', $uri);
        $this->assertStringContainsString('secret=MFRGGZDF', $uri);
        $this->assertStringContainsString('admin%40gamimed.local', $uri);
    }
}
