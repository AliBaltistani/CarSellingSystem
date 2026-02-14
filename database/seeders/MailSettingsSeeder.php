<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class MailSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $mailSettings = [
            ['key' => 'mail_mailer', 'value' => 'smtp', 'type' => 'string', 'group' => 'mail'],
            ['key' => 'mail_host', 'value' => '', 'type' => 'string', 'group' => 'mail'],
            ['key' => 'mail_port', 'value' => '587', 'type' => 'string', 'group' => 'mail'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'string', 'group' => 'mail'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'string', 'group' => 'mail'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'type' => 'string', 'group' => 'mail'],
            ['key' => 'mail_from_address', 'value' => '', 'type' => 'string', 'group' => 'mail'],
            ['key' => 'mail_from_name', 'value' => '', 'type' => 'string', 'group' => 'mail'],
            ['key' => 'email_verification_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'general'],
        ];

        foreach ($mailSettings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
