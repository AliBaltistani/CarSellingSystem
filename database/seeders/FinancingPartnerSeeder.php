<?php

namespace Database\Seeders;

use App\Models\FinancingPartner;
use Illuminate\Database\Seeder;

class FinancingPartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Emirates NBD',
                'website_url' => 'https://www.emiratesnbd.com',
                'description' => 'Leading UAE banking group',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Abu Dhabi Commercial Bank',
                'website_url' => 'https://www.adcb.com',
                'description' => 'Premier UAE bank',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Dubai Islamic Bank',
                'website_url' => 'https://www.dib.ae',
                'description' => 'Islamic banking solutions',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Mashreq Bank',
                'website_url' => 'https://www.mashreqbank.com',
                'description' => 'Private sector bank',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'First Abu Dhabi Bank',
                'website_url' => 'https://www.bankfab.com',
                'description' => 'Largest bank in UAE',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'RAK Bank',
                'website_url' => 'https://www.rakbank.ae',
                'description' => 'Personal and business banking',
                'is_active' => true,
                'order' => 6,
            ],
        ];

        foreach ($partners as $partner) {
            // Note: Logo images would need to be uploaded via admin panel
            // This seeder creates the data structure only
            FinancingPartner::create(array_merge($partner, [
                'logo' => 'financing-partners/placeholder.png', // placeholder
            ]));
        }
    }
}
