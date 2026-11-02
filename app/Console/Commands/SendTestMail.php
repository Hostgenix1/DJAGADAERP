<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    protected $signature = 'mail:test {email : Recipient address}';

    protected $description = 'Send a test email using the configured SMTP settings';

    public function handle(): int
    {
        $email = $this->argument('email');

        try {
            Mail::raw(
                'This is a test email from '.config('app.name').' sent at '.now()->toDateTimeString().'.',
                function ($message) use ($email) {
                    $message->to($email)->subject(config('app.name').' mail test');
                }
            );
        } catch (\Throwable $e) {
            $this->error('Mail could not be sent: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info('Test email sent to '.$email.'.');

        return self::SUCCESS;
    }
}
