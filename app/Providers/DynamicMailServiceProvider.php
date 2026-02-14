<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class DynamicMailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Only override if the settings table exists and has mail settings
        try {
            if (!Schema::hasTable('settings')) {
                return;
            }

            $mailHost = Setting::get('mail_host');

            // Only override config if SMTP credentials are configured in admin panel
            if ($mailHost) {
                $mailer = Setting::get('mail_mailer') ?? 'smtp';
                $encryption = Setting::get('mail_encryption') ?? 'tls';

                Config::set('mail.default', $mailer);
                Config::set('mail.mailers.smtp.host', $mailHost);
                Config::set('mail.mailers.smtp.port', Setting::get('mail_port') ?? 587);
                Config::set('mail.mailers.smtp.username', Setting::get('mail_username'));
                Config::set('mail.mailers.smtp.password', Setting::get('mail_password'));
                Config::set('mail.mailers.smtp.encryption', $encryption === 'none' ? null : $encryption);

                $fromAddress = Setting::get('mail_from_address');
                $fromName = Setting::get('mail_from_name');

                if ($fromAddress) {
                    Config::set('mail.from.address', $fromAddress);
                }
                if ($fromName) {
                    Config::set('mail.from.name', $fromName);
                }
            }
        } catch (\Exception $e) {
            // Silently fail — database may not be available during migrations etc.
        }
    }
}
