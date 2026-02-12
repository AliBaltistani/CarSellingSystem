<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Branding
            ['key' => 'site_logo', 'value' => null, 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'string', 'group' => 'general'],

            // General Settings
            ['key' => 'site_name', 'value' => 'Xenon Motors', 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Buy and Sell Quality Cars', 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_email', 'value' => 'info@xenonmotors.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_phone', 'value' => '+971 50 123 4567', 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_address', 'value' => 'Dubai, UAE', 'type' => 'string', 'group' => 'general'],
            ['key' => 'default_currency', 'value' => 'AED', 'type' => 'string', 'group' => 'general'],
            ['key' => 'distance_unit', 'value' => 'km', 'type' => 'string', 'group' => 'general'],
            
            // Contact Settings
            ['key' => 'whatsapp_number', 'value' => '+971501234567', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/xenonmotors', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/xenonmotors', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/xenonmotors', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'linkedin_url', 'value' => '', 'type' => 'string', 'group' => 'contact'],
            
            // Display Settings
            ['key' => 'cars_per_page', 'value' => '12', 'type' => 'integer', 'group' => 'display'],
            ['key' => 'featured_cars_limit', 'value' => '6', 'type' => 'integer', 'group' => 'display'],
            ['key' => 'latest_cars_limit', 'value' => '8', 'type' => 'integer', 'group' => 'display'],
            ['key' => 'show_sold_cars', 'value' => 'false', 'type' => 'boolean', 'group' => 'display'],
            
            // Location Settings
            ['key' => 'default_radius', 'value' => '50', 'type' => 'integer', 'group' => 'location'],
            ['key' => 'default_country', 'value' => 'UAE', 'type' => 'string', 'group' => 'location'],
            ['key' => 'default_city', 'value' => 'Dubai', 'type' => 'string', 'group' => 'location'],
            
            // SEO Settings
            ['key' => 'meta_title', 'value' => 'Xenon Motors - Buy and Sell Quality Cars', 'type' => 'string', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'Find your perfect car at Xenon Motors. Browse quality new and used cars for sale. Contact sellers directly via WhatsApp.', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'meta_keywords', 'value' => 'cars, buy car, sell car, used cars, Dubai cars, UAE cars', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'google_analytics_id', 'value' => '', 'type' => 'string', 'group' => 'seo'],
            ['key' => 'google_tag_manager_id', 'value' => '', 'type' => 'string', 'group' => 'seo'],
            
            // Homepage Content
            ['key' => 'hero_title', 'value' => 'Find Your Perfect Car', 'type' => 'string', 'group' => 'homepage'],
            ['key' => 'hero_subtitle', 'value' => 'Browse thousands of quality cars near you', 'type' => 'string', 'group' => 'homepage'],
            ['key' => 'stats_total_cars', 'value' => '500', 'type' => 'integer', 'group' => 'homepage'],
            ['key' => 'stats_happy_customers', 'value' => '1200', 'type' => 'integer', 'group' => 'homepage'],
            ['key' => 'stats_cities_covered', 'value' => '15', 'type' => 'integer', 'group' => 'homepage'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
