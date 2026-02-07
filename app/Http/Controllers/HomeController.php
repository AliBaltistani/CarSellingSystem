<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Banner;
use App\Services\SeoService;

class HomeController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    public function index()
    {
        $categories = Category::active()
            ->withCount(['cars' => fn($q) => $q->published()->available()])
            ->orderBy('order')
            ->get();

        $featuredCars = Car::with(['category', 'images'])
            ->published()
            ->available()
            ->featured()
            ->latest()
            ->limit((int) Setting::get('featured_cars_limit', 6))
            ->get();

        $latestCars = Car::with(['category', 'images'])
            ->published()
            ->available()
            ->latest()
            ->limit((int) Setting::get('latest_cars_limit', 8))
            ->get();

        // Fetch active banners for the slider
        $banners = Banner::getDisplayBanners();

        // Get unique car makes for the search dropdown
        $makes = Car::published()
            ->available()
            ->distinct()
            ->pluck('make')
            ->filter()
            ->sort()
            ->values();

        $stats = [
            'total_cars' => Setting::get('stats_total_cars', Car::published()->available()->count()),
            'happy_customers' => Setting::get('stats_happy_customers', 1000),
            'cities_covered' => Setting::get('stats_cities_covered', 10),
        ];

        $seo = [
            'title' => Setting::get('meta_title', config('app.name')),
            'description' => Setting::get('meta_description'),
            'keywords' => Setting::get('meta_keywords'),
        ];

        return view('home', compact(
            'categories',
            'featuredCars',
            'latestCars',
            'banners',
            'makes',
            'stats',
            'seo'
        ));
    }
}

