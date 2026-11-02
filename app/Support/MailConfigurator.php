<?php

namespace App\Support;

use App\Services\SettingsService;

class MailConfigurator
{
    /**
     * Apply the SMTP settings saved in the admin panel to the runtime mail
     * configuration. Without this, the SMTP tab would be decorative and mail
     * would always use the values from .env.
     */
    public static function apply(): void
    {
        $settings = app(SettingsService::class);

        $host = $settings->get('company_smtp_host');
        if (! $host) {
            return;
        }

        $port = (int) ($settings->get('company_smtp_port') ?: 587);
        $encryption = $settings->get('company_smtp_encryption');

        if ($encryption === 'none') {
            $encryption = null;
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => $port,
            'mail.mailers.smtp.username' => $settings->get('company_smtp_username'),
            'mail.mailers.smtp.password' => $settings->get('company_smtp_password'),
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.from.address' => $settings->get('company_smtp_from_email') ?: config('mail.from.address'),
            'mail.from.name' => $settings->get('company_smtp_from_name') ?: config('mail.from.name'),
        ]);
    }
}
