<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'customer_name' => 'Ahmed Al Rashid',
                'designation' => 'Business Owner',
                'rating' => 5,
                'review' => 'Excellent service! I found my dream car within a week. The team was very professional and helped me through the entire process. Highly recommended for anyone looking to buy or sell a car.',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'customer_name' => 'Sarah Johnson',
                'designation' => 'Marketing Executive',
                'rating' => 5,
                'review' => 'I sold my car through Xenon Motors and got a great price. The whole process was smooth and transparent. The WhatsApp communication made everything so convenient.',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'customer_name' => 'Mohammed Hassan',
                'designation' => 'Engineer',
                'rating' => 4,
                'review' => 'Very happy with my purchase. The website made it easy to compare different cars and the filters helped me find exactly what I was looking for. Great platform!',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'customer_name' => 'Emily Chen',
                'designation' => 'Doctor',
                'rating' => 5,
                'review' => 'The best car buying experience I\'ve ever had. The team was responsive, the car was exactly as described, and the paperwork was handled professionally.',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'customer_name' => 'Omar Khalil',
                'designation' => 'Restaurant Owner',
                'rating' => 5,
                'review' => 'I bought a certified pre-owned car and it exceeded my expectations. Xenon Motors really cares about customer satisfaction. Will definitely recommend to friends.',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'customer_name' => 'Lisa Thompson',
                'designation' => 'Teacher',
                'rating' => 4,
                'review' => 'Found a great family car at an affordable price. The location search feature helped me find cars near my neighborhood. Very convenient service.',
                'is_active' => true,
                'order' => 6,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
