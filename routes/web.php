<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;
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
});

require __DIR__.'/auth.php';

