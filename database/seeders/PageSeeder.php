<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Finance',
                'slug' => 'finance',
                'excerpt' => 'Flexible financing options to get you on the road faster.',
                'content' => '
<h2>Car Financing Made Easy</h2>
<p>At Xenon Motors, we understand that purchasing a vehicle is a significant investment. That\'s why we offer flexible financing solutions tailored to your needs and budget.</p>

<h3>Why Finance with Us?</h3>
<ul>
    <li><strong>Competitive Rates</strong> - We work with multiple lenders to get you the best rates available</li>
    <li><strong>Quick Approval</strong> - Get pre-approved in minutes with our simple online application</li>
    <li><strong>Flexible Terms</strong> - Choose from a variety of loan terms to fit your budget</li>
    <li><strong>All Credit Welcome</strong> - We have options for all credit situations</li>
</ul>

<h3>Financing Options</h3>
<p>We offer several financing solutions:</p>
<ul>
    <li>Traditional auto loans</li>
    <li>Lease-to-own programs</li>
    <li>Bank financing</li>
    <li>In-house financing</li>
</ul>

<h3>How to Apply</h3>
<p>Getting started is easy! Simply browse our inventory, select your dream car, and click on the financing option. Our team will guide you through the entire process.</p>
',
                'template' => 'sidebar',
                'is_active' => true,
                'show_in_header' => true,
                'header_order' => 3,
                'show_in_footer' => true,
                'footer_order' => 1,
                'meta_title' => 'Car Financing Options | Xenon Motors',
                'meta_description' => 'Explore flexible car financing options at Xenon Motors. Competitive rates, quick approval, and terms that fit your budget.',
            ],
            [
                'title' => 'Our Offers',
                'slug' => 'our-offers',
                'excerpt' => 'Discover our latest deals and special offers on premium vehicles.',
                'content' => '
<h2>Special Offers & Promotions</h2>
<p>Take advantage of our exclusive deals and limited-time offers on select vehicles.</p>

<h3>Current Promotions</h3>

<div style="background: #f8fafc; padding: 20px; border-radius: 10px; margin: 20px 0;">
<h4>🚗 0% APR Financing</h4>
<p>Get 0% APR financing for up to 60 months on select new vehicles. Limited time offer!</p>
</div>

<div style="background: #f8fafc; padding: 20px; border-radius: 10px; margin: 20px 0;">
<h4>💰 Trade-In Bonus</h4>
<p>Receive an extra AED 2,000 on your trade-in when you purchase any vehicle from our premium selection.</p>
</div>

<div style="background: #f8fafc; padding: 20px; border-radius: 10px; margin: 20px 0;">
<h4>🎁 Free Service Package</h4>
<p>Complimentary 2-year service package included with every purchase this month.</p>
</div>

<h3>Why Shop with Us?</h3>
<ul>
    <li>Certified pre-owned vehicles</li>
    <li>Comprehensive vehicle inspections</li>
    <li>Extended warranty options</li>
    <li>Price match guarantee</li>
</ul>
',
                'template' => 'default',
                'is_active' => true,
                'show_in_header' => true,
                'header_order' => 4,
                'show_in_footer' => true,
                'footer_order' => 2,
                'meta_title' => 'Special Offers & Deals | Xenon Motors',
                'meta_description' => 'Browse our latest special offers, promotions, and exclusive deals on premium vehicles at Xenon Motors.',
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'excerpt' => 'Learn more about Xenon Motors and our commitment to quality.',
                'content' => '
<h2>Our Story</h2>
<p>Founded with a passion for exceptional automobiles, Xenon Motors has grown to become one of the UAE\'s most trusted automotive dealerships.</p>

<h3>Our Mission</h3>
<p>To provide our customers with an unparalleled car buying experience through transparency, integrity, and exceptional service.</p>

<h3>Our Values</h3>
<ul>
    <li><strong>Transparency</strong> - No hidden fees, no surprises. What you see is what you get.</li>
    <li><strong>Quality</strong> - Every vehicle undergoes rigorous inspection before listing.</li>
    <li><strong>Customer First</strong> - Your satisfaction is our top priority.</li>
    <li><strong>Integrity</strong> - We build lasting relationships through honest dealings.</li>
</ul>

<h3>Why Choose Xenon Motors?</h3>
<p>With years of experience in the automotive industry, we\'ve helped thousands of customers find their perfect vehicle. Our team of experts is dedicated to making your car buying journey smooth and enjoyable.</p>

<h3>Our Achievements</h3>
<ul>
    <li>10,000+ Happy Customers</li>
    <li>5,000+ Vehicles Sold</li>
    <li>Award-winning Customer Service</li>
    <li>ISO Certified Dealership</li>
</ul>
',
                'template' => 'full-width',
                'is_active' => true,
                'show_in_header' => true,
                'header_order' => 5,
                'show_in_footer' => true,
                'footer_order' => 3,
                'meta_title' => 'About Us | Xenon Motors',
                'meta_description' => 'Learn about Xenon Motors, our mission, values, and commitment to providing the best car buying experience in the UAE.',
            ],
            [
                'title' => 'FAQs',
                'slug' => 'faqs',
                'excerpt' => 'Find answers to commonly asked questions about our services.',
                'content' => '
<h3>How do I schedule a test drive?</h3>
<p>You can schedule a test drive by clicking the "Schedule Test Drive" button on any vehicle listing page. Our team will contact you to confirm the appointment.</p>

<h3>What documents do I need to purchase a car?</h3>
<p>You\'ll need a valid Emirates ID, driver\'s license, proof of income, and proof of residence. Additional documents may be required for financing.</p>

<h3>Do you offer warranty on used cars?</h3>
<p>Yes! All our certified pre-owned vehicles come with a minimum 6-month warranty covering major mechanical components.</p>

<h3>Can I trade in my current vehicle?</h3>
<p>Absolutely! We accept trade-ins and offer competitive valuations. You can get an instant estimate using our online trade-in tool.</p>

<h3>What financing options are available?</h3>
<p>We offer multiple financing options including bank loans, in-house financing, and lease-to-own programs. Our finance team will help you find the best option for your situation.</p>

<h3>How long does the buying process take?</h3>
<p>For cash purchases, you can drive away the same day! Financing applications typically take 1-2 business days for approval.</p>

<h3>Do you deliver vehicles?</h3>
<p>Yes, we offer free delivery within Dubai and competitive rates for delivery across the UAE.</p>

<h3>What is your return policy?</h3>
<p>We offer a 7-day/500km money-back guarantee on all purchases. If you\'re not satisfied, simply return the vehicle for a full refund.</p>
',
                'template' => 'faq',
                'is_active' => true,
                'show_in_header' => false,
                'header_order' => 0,
                'show_in_footer' => true,
                'footer_order' => 4,
                'meta_title' => 'Frequently Asked Questions | Xenon Motors',
                'meta_description' => 'Find answers to common questions about buying cars, financing, trade-ins, and more at Xenon Motors.',
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'excerpt' => 'Get in touch with our team for any questions or assistance.',
                'content' => '
<h3>We\'re Here to Help</h3>
<p>Have questions about a vehicle, financing, or our services? Our friendly team is ready to assist you.</p>

<h4>Business Hours</h4>
<ul>
    <li><strong>Saturday - Thursday:</strong> 9:00 AM - 9:00 PM</li>
    <li><strong>Friday:</strong> 2:00 PM - 9:00 PM</li>
</ul>

<h4>Visit Our Showroom</h4>
<p>Experience our vehicles in person at our state-of-the-art showroom. Our sales consultants are available to answer all your questions and help you find the perfect car.</p>
',
                'template' => 'contact',
                'is_active' => true,
                'show_in_header' => true,
                'header_order' => 6,
                'show_in_footer' => true,
                'footer_order' => 5,
                'meta_title' => 'Contact Us | Xenon Motors',
                'meta_description' => 'Contact Xenon Motors for inquiries about vehicles, financing, or services. Visit our showroom or reach out via phone, email, or WhatsApp.',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'excerpt' => 'Learn how we collect, use, and protect your personal information.',
                'content' => '
<h2>Privacy Policy</h2>
<p><em>Last updated: February 2024</em></p>

<h3>Introduction</h3>
<p>Xenon Motors ("we", "our", or "us") respects your privacy and is committed to protecting your personal data. This privacy policy explains how we collect, use, and safeguard your information when you visit our website or use our services.</p>

<h3>Information We Collect</h3>
<p>We may collect the following types of information:</p>
<ul>
    <li><strong>Personal Information:</strong> Name, email address, phone number, address</li>
    <li><strong>Financial Information:</strong> Payment details (processed securely through our payment providers)</li>
    <li><strong>Vehicle Preferences:</strong> Search history, favorite vehicles, inquiry details</li>
    <li><strong>Technical Data:</strong> IP address, browser type, device information</li>
</ul>

<h3>How We Use Your Information</h3>
<ul>
    <li>Process your vehicle inquiries and purchases</li>
    <li>Provide customer support and respond to your requests</li>
    <li>Send you relevant updates about vehicles and offers (with your consent)</li>
    <li>Improve our website and services</li>
    <li>Comply with legal obligations</li>
</ul>

<h3>Data Security</h3>
<p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

<h3>Your Rights</h3>
<p>You have the right to:</p>
<ul>
    <li>Access your personal data</li>
    <li>Correct inaccurate data</li>
    <li>Request deletion of your data</li>
    <li>Opt-out of marketing communications</li>
</ul>

<h3>Contact Us</h3>
<p>If you have any questions about this privacy policy, please contact us at privacy@xenonmotors.ae</p>
',
                'template' => 'sidebar',
                'is_active' => true,
                'show_in_header' => false,
                'header_order' => 0,
                'show_in_footer' => true,
                'footer_order' => 10,
                'meta_title' => 'Privacy Policy | Xenon Motors',
                'meta_description' => 'Read our privacy policy to understand how Xenon Motors collects, uses, and protects your personal information.',
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'excerpt' => 'Read our terms and conditions for using our services.',
                'content' => '
<h2>Terms of Service</h2>
<p><em>Last updated: February 2024</em></p>

<h3>Agreement to Terms</h3>
<p>By accessing our website or using our services, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p>

<h3>Use of Our Services</h3>
<p>You may use our website and services only for lawful purposes. You agree not to:</p>
<ul>
    <li>Use our services for any illegal purpose</li>
    <li>Attempt to gain unauthorized access to our systems</li>
    <li>Transmit viruses or malicious code</li>
    <li>Interfere with other users\' access to our services</li>
</ul>

<h3>Vehicle Listings</h3>
<p>We strive to provide accurate information about all vehicles listed on our platform. However:</p>
<ul>
    <li>Prices and availability are subject to change without notice</li>
    <li>Vehicle specifications should be verified before purchase</li>
    <li>Photos may not represent the exact vehicle available</li>
</ul>

<h3>Purchases and Payments</h3>
<p>All vehicle purchases are subject to:</p>
<ul>
    <li>Verification of payment</li>
    <li>Completion of required documentation</li>
    <li>Vehicle availability at time of purchase</li>
</ul>

<h3>Limitation of Liability</h3>
<p>Xenon Motors shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of our services.</p>

<h3>Changes to Terms</h3>
<p>We reserve the right to modify these terms at any time. Continued use of our services after changes constitutes acceptance of the new terms.</p>

<h3>Contact</h3>
<p>For questions about these terms, please contact us at legal@xenonmotors.ae</p>
',
                'template' => 'sidebar',
                'is_active' => true,
                'show_in_header' => false,
                'header_order' => 0,
                'show_in_footer' => true,
                'footer_order' => 11,
                'meta_title' => 'Terms of Service | Xenon Motors',
                'meta_description' => 'Read the terms and conditions for using Xenon Motors website and services.',
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
