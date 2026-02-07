<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_cars' => Car::count(),
            'active_listings' => Car::published()->available()->count(),
            'sold_cars' => Car::where('status', 'sold')->count(),
            'pending_cars' => Car::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'new_inquiries' => Inquiry::where('status', 'new')->count(),
            'total_views' => Car::sum('views_count'),
        ];

        $recentCars = Car::with(['category', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        $recentInquiries = Inquiry::with(['car', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        $carsByCategory = Category::withCount(['cars' => function ($query) {
            $query->published()->available();
        }])->get();

        $monthlySales = Car::where('status', 'sold')
            ->whereYear('sold_at', date('Y'))
            ->select(
                DB::raw('MONTH(sold_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(price) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentCars',
            'recentInquiries',
            'carsByCategory',
            'monthlySales'
        ));
    }
}
