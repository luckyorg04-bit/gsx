<?php

namespace Tests\Feature;

use Tests\TestCase;

class MailConfigurationTest extends TestCase
{
    public function test_laravel_12_mailer_configuration_uses_named_transports_and_gsx_sender_name(): void
    {
        $this->assertNotEmpty(config('mail.default'));
        $this->assertArrayHasKey('smtp', config('mail.mailers'));
        $this->assertArrayHasKey('array', config('mail.mailers'));
        $this->assertSame(config('app.name'), config('mail.from.name'));
    }
}
