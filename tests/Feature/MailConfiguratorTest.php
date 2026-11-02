<?php

namespace Tests\Feature;

use App\Services\SettingsService;
use App\Support\MailConfigurator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailConfiguratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_saved_smtp_settings_override_the_mail_configuration(): void
    {
        $settings = app(SettingsService::class);
        $settings->set('company_smtp_host', 'smtp.example.com');
        $settings->set('company_smtp_port', '2525');
        $settings->set('company_smtp_username', 'mailer@example.com');
        $settings->set('company_smtp_password', 'secret');
        $settings->set('company_smtp_encryption', 'tls');
        $settings->set('company_smtp_from_email', 'billing@example.com');
        $settings->set('company_smtp_from_name', 'DJAGADA Billing');

        MailConfigurator::apply();

        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('smtp.example.com', config('mail.mailers.smtp.host'));
        $this->assertSame(2525, config('mail.mailers.smtp.port'));
        $this->assertSame('mailer@example.com', config('mail.mailers.smtp.username'));
        $this->assertSame('tls', config('mail.mailers.smtp.encryption'));
        $this->assertSame('billing@example.com', config('mail.from.address'));
        $this->assertSame('DJAGADA Billing', config('mail.from.name'));
    }

    public function test_mail_configuration_is_untouched_without_a_smtp_host(): void
    {
        config(['mail.default' => 'log']);

        MailConfigurator::apply();

        $this->assertSame('log', config('mail.default'));
    }
}
