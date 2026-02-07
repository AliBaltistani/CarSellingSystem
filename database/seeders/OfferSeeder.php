<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $offers = [
            [
                'title' => 'Toyota Excellence Package',
                'badge' => '100% DOWN PAYMENT',
                'price_label' => 'PREMIUM',
                'price_from' => 85000,
                'description' => 'Get the best Toyota models with our exclusive financing package. Zero hidden fees and flexible payment terms.',
                'features' => [
                    'Free registration for 1 year',
                    '3 years warranty coverage',
                    'Free service for 2 years',
                    'Road assistance included',
                ],
                'cta_text' => 'Apply Now',
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'title' => 'Mercedes-Benz Premium',
                'badge' => 'FREE INSURANCE',
                'price_label' => 'LUXURY',
                'price_from' => 185000,
                'description' => 'Drive luxury with comprehensive insurance coverage included for the first year.',
                'features' => [
                    '1 year free insurance',
                    'Mercedes-Benz warranty',
                    'Concierge service',
                    'Airport pickup included',
                ],
                'cta_text' => 'Explore',
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'title' => 'Family SUV Special',
                'badge' => 'BEST VALUE',
                'price_label' => 'FAMILY',
                'price_from' => 65000,
                'description' => 'Perfect for families looking for spacious and reliable SUVs at competitive prices.',
                'features' => [
                    '7-seater options available',
                    'Extended warranty',
                    'Child safety features',
                    'Entertainment system',
                ],
                'cta_text' => 'View SUVs',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'title' => 'First-Time Buyer',
                'badge' => 'EASY APPROVAL',
                'price_label' => 'STARTER',
                'price_from' => 35000,
                'description' => 'Special financing options for first-time car buyers with easy approval process.',
                'features' => [
                    'No credit history required',
                    'Low monthly payments',
                    'Flexible terms',
                    'Quick approval',
                ],
                'cta_text' => 'Get Started',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'title' => 'Trade-In Bonus',
                'badge' => 'EXTRA 10%',
                'price_label' => 'TRADE-IN',
                'price_from' => null,
                'description' => 'Get an extra 10% bonus on your trade-in when you upgrade to a new vehicle.',
                'features' => [
                    'Free car valuation',
                    'Same-day trade-in',
                    'Best market rates',
                    'Hassle-free process',
                ],
                'cta_text' => 'Value My Car',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'title' => 'Corporate Fleet',
                'badge' => 'BUSINESS',
                'price_label' => 'FLEET',
                'price_from' => 250000,
                'description' => 'Special fleet pricing for businesses. Volume discounts and dedicated support.',
                'features' => [
                    'Volume discounts',
                    'Fleet management',
                    'Priority service',
                    'Dedicated account manager',
                ],
                'cta_text' => 'Contact Sales',
                'is_active' => true,
                'order' => 6,
            ],
        ];

        foreach ($offers as $offer) {
            Offer::create($offer);
        }
    }
}
