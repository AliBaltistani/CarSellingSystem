<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 mb-8 shadow-lg">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="text-amber-100 mt-1">Manage your listings, favorites, and inquiries all in one place.</p>
                    </div>
                    <a href="{{ route('cars.create') }}" 
                        class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-colors shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Listing
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- My Listings -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">My Listings</p>
                            <p class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['listings'] ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('cars.my-listings') }}" class="text-sm text-blue-600 hover:text-blue-700 mt-3 inline-block">View all →</a>
                </div>

                <!-- Favorites -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">Saved Favorites</p>
                            <p class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['favorites'] ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('favorites.index') }}" class="text-sm text-red-500 hover:text-red-600 mt-3 inline-block">View favorites →</a>
                </div>

                <!-- Inquiries Received -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">Inquiries Received</p>
                            <p class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['inquiries_received'] ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                    </div>
                    <span class="text-sm text-slate-400 mt-3 inline-block">From your listings</span>
                </div>

                <!-- Total Views -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">Total Views</p>
                            <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_views'] ?? 0) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                    </div>
                    <span class="text-sm text-slate-400 mt-3 inline-block">On all your listings</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Quick Actions -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                        <h2 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h2>
                        <div class="space-y-3">
                            <a href="{{ route('cars.create') }}" 
                                class="flex items-center p-3 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors group">
                                <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">Sell Your Car</p>
                                    <p class="text-xs text-slate-500">Create a new listing</p>
                                </div>
                            </a>

                            <a href="{{ route('cars.my-listings') }}" 
                                class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">My Listings</p>
                                    <p class="text-xs text-slate-500">Manage your cars</p>
                                </div>
                            </a>

                            <a href="{{ route('favorites.index') }}" 
                                class="flex items-center p-3 bg-red-50 rounded-lg hover:bg-red-100 transition-colors group">
                                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">Favorites</p>
                                    <p class="text-xs text-slate-500">View saved cars</p>
                                </div>
                            </a>

                            <a href="{{ route('profile.edit') }}" 
                                class="flex items-center p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors group">
                                <div class="w-10 h-10 bg-slate-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">Edit Profile</p>
                                    <p class="text-xs text-slate-500">Update your info</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Listings -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100">
                        <div class="p-6 border-b border-slate-100">
                            <div class="flex justify-between items-center">
                                <h2 class="text-lg font-semibold text-slate-900">My Recent Listings</h2>
                                <a href="{{ route('cars.my-listings') }}" class="text-sm text-amber-600 hover:text-amber-700">View All</a>
                            </div>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($recentListings ?? [] as $car)
                                <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                    <div class="flex items-center">
                                        <img src="{{ $car->main_image }}" alt="{{ $car->title }}" 
                                            class="w-16 h-12 object-cover rounded-lg mr-4">
                                        <div>
                                            <p class="font-medium text-slate-900">{{ Str::limit($car->title, 30) }}</p>
                                            <p class="text-sm text-slate-500">{{ $car->formatted_price }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $car->status === 'available' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                            {{ $car->status === 'sold' ? 'bg-slate-100 text-slate-600' : '' }}
                                            {{ $car->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}">
                                            {{ ucfirst($car->status) }}
                                        </span>
                                        <a href="{{ route('cars.edit', $car) }}" class="text-slate-400 hover:text-amber-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 mb-4">You haven't listed any cars yet</p>
                                    <a href="{{ route('cars.create') }}" 
                                        class="inline-flex items-center px-4 py-2 bg-amber-500 text-white font-medium rounded-lg hover:bg-amber-600 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Create Your First Listing
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
