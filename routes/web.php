<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\FinancingPartnerController as AdminFinancingPartnerController;
use App\Http\Controllers\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\AttributeGroupController as AdminAttributeGroupController;
use App\Http\Controllers\Admin\AttributeController as AdminAttributeController;
use App\Http\Controllers\Admin\DropdownOptionController as AdminDropdownOptionController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Car Routes
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/search', [CarController::class, 'search'])->name('cars.search');
Route::get('/cars/category/{category}', [CarController::class, 'byCategory'])->name('cars.category');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

// Inquiry Routes (AJAX)
Route::post('/cars/{car}/inquiry', [InquiryController::class, 'store'])->name('inquiries.store');

// Dynamic Pages (must be after other routes to avoid conflicts)
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// Contact Form Submission
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Offer Detail Page (Public)
Route::get('/offers/{offer}', [CheckoutController::class, 'show'])->name('offers.show');

// Stripe Webhook (excluded from CSRF via middleware)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // User Dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        $stats = [
            'listings' => $user->cars()->count(),
            'favorites' => $user->favorites()->count(),
            'inquiries_received' => \App\Models\Inquiry::whereHas('car', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'total_views' => $user->cars()->sum('views_count'),
        ];
        
        $recentListings = $user->cars()->latest()->take(5)->get();
        
        return view('dashboard', compact('stats', 'recentListings'));
    })->middleware('verified')->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{car}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    
    // User Car Listings
    Route::get('/my-cars', [CarController::class, 'myListings'])->name('cars.my-listings');
    Route::get('/my-cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/my-cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/my-cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/my-cars/{car}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/my-cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
    
    // User Inquiries
    Route::get('/my-inquiries', [InquiryController::class, 'myInquiries'])->name('inquiries.my-inquiries');

    // Checkout & Orders
    Route::post('/offers/{offer}/checkout', [CheckoutController::class, 'checkout'])->name('offers.checkout');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('orders.my-orders');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Cars Management
        Route::resource('cars', AdminCarController::class);
        Route::post('cars/{car}/toggle-featured', [AdminCarController::class, 'toggleFeatured'])
            ->name('cars.toggle-featured');
        Route::post('cars/{car}/toggle-published', [AdminCarController::class, 'togglePublished'])
            ->name('cars.toggle-published');
        Route::delete('cars/images/{image}', [AdminCarController::class, 'deleteImage'])
            ->name('cars.delete-image');
        Route::post('cars/images/{image}/primary', [AdminCarController::class, 'setPrimaryImage'])
            ->name('cars.set-primary-image');
        
        // Categories Management
        Route::resource('categories', AdminCategoryController::class)->except('show');
        Route::post('categories/reorder', [AdminCategoryController::class, 'reorder'])
            ->name('categories.reorder');
        
        // Users Management
        Route::resource('users', AdminUserController::class)->except('show');
        Route::post('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])
            ->name('users.toggle-active');
        
        // Inquiries Management
        Route::get('inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('inquiries/{inquiry}/status', [AdminInquiryController::class, 'updateStatus'])
            ->name('inquiries.update-status');
        Route::delete('inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])
            ->name('inquiries.destroy');
        Route::post('inquiries/bulk-status', [AdminInquiryController::class, 'bulkUpdateStatus'])
            ->name('inquiries.bulk-status');
        
        // Settings
        Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::get('settings/create', [AdminSettingController::class, 'create'])->name('settings.create');
        Route::post('settings', [AdminSettingController::class, 'store'])->name('settings.store');
        Route::delete('settings/{setting}', [AdminSettingController::class, 'destroy'])
            ->name('settings.destroy');

        // Locations Management
        Route::get('locations/search-api', [AdminLocationController::class, 'searchApi'])
            ->name('locations.search-api');
        Route::resource('locations', AdminLocationController::class)->except('show');
        Route::post('locations/{location}/toggle-active', [AdminLocationController::class, 'toggleActive'])
            ->name('locations.toggle-active');

        // Banners Management
        Route::resource('banners', AdminBannerController::class)->except('show');
        Route::post('banners/{banner}/toggle-active', [AdminBannerController::class, 'toggleActive'])
            ->name('banners.toggle-active');
        Route::post('banners/reorder', [AdminBannerController::class, 'reorder'])
            ->name('banners.reorder');

        // Testimonials Management
        Route::resource('testimonials', AdminTestimonialController::class)->except('show');
        Route::post('testimonials/{testimonial}/toggle-active', [AdminTestimonialController::class, 'toggleActive'])
            ->name('testimonials.toggle-active');

        // Financing Partners Management
        Route::resource('financing-partners', AdminFinancingPartnerController::class)->except('show');
        Route::post('financing-partners/{financing_partner}/toggle-active', [AdminFinancingPartnerController::class, 'toggleActive'])
            ->name('financing-partners.toggle-active');

        // Offers Management
        Route::resource('offers', AdminOfferController::class)->except('show');
        Route::post('offers/{offer}/toggle-active', [AdminOfferController::class, 'toggleActive'])
            ->name('offers.toggle-active');

        // Orders Management
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

        // Dynamic Attribute Engine
        Route::resource('attribute-groups', AdminAttributeGroupController::class)->except('show');
        Route::post('attribute-groups/{attribute_group}/toggle-active', [AdminAttributeGroupController::class, 'toggleActive'])
            ->name('attribute-groups.toggle-active');

        Route::resource('attributes', AdminAttributeController::class)->except('show');
        Route::post('attributes/{attribute}/toggle-active', [AdminAttributeController::class, 'toggleActive'])
            ->name('attributes.toggle-active');
        Route::get('attributes/{attribute}/categories', [AdminAttributeController::class, 'categories'])
            ->name('attributes.categories');
        Route::post('attributes/{attribute}/categories', [AdminAttributeController::class, 'updateCategories'])
            ->name('attributes.update-categories');
        
        // Attribute Import/Export
        Route::get('attributes-import-export', [\App\Http\Controllers\Admin\AttributeImportExportController::class, 'showImportForm'])
            ->name('attributes.import-export');
        Route::get('attributes-export', [\App\Http\Controllers\Admin\AttributeImportExportController::class, 'export'])
            ->name('attributes.export');
        Route::get('attributes-export-csv', [\App\Http\Controllers\Admin\AttributeImportExportController::class, 'exportCsv'])
            ->name('attributes.export-csv');
        Route::post('attributes-import', [\App\Http\Controllers\Admin\AttributeImportExportController::class, 'import'])
            ->name('attributes.import');

        // Pages Management
        Route::resource('pages', \App\Http\Controllers\Admin\AdminPageController::class)->except('show');
        Route::post('pages/{page}/toggle-active', [\App\Http\Controllers\Admin\AdminPageController::class, 'toggleActive'])
            ->name('pages.toggle-active');

        // Dropdown Options Management
        Route::resource('dropdown-options', AdminDropdownOptionController::class)->except('show');
        Route::post('dropdown-options/{dropdownOption}/toggle-active', [AdminDropdownOptionController::class, 'toggleActive'])
            ->name('dropdown-options.toggle-active');
    });

/*
|--------------------------------------------------------------------------
| API Routes (Public)
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {
    // Location search using Nominatim
    Route::get('/locations/search', [LocationController::class, 'search'])->name('api.locations.search');
    Route::get('/locations/reverse', [LocationController::class, 'reverse'])->name('api.locations.reverse');
    Route::get('/locations/combined', [LocationController::class, 'searchCombined'])->name('api.locations.combined');
    Route::post('/locations/create', [LocationController::class, 'createFromApi'])->name('api.locations.create');
    
    // Car search suggestions
    Route::get('/cars/suggestions', [SearchController::class, 'suggestions'])->name('api.cars.suggestions');
    
    // Category attributes for dynamic forms
    Route::get('/categories/{category}/attributes', [\App\Http\Controllers\Api\CategoryAttributeController::class, 'index'])
        ->name('api.categories.attributes');
});

require __DIR__.'/auth.php';

