<x-layouts.admin title="Dashboard">
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-sm sm:text-base text-slate-600">Welcome back, {{ auth()->user()->name }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Cars</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_cars'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Active Listings</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['active_cars'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Users</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['total_users'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">New Inquiries</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($stats['new_inquiries'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Cars -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-slate-100">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-slate-900">Recent Listings</h2>
                    <a href="{{ route('admin.cars.index') }}" class="text-sm text-amber-600 hover:text-amber-700">View All</a>
                </div>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentCars ?? [] as $car)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50">
                        <div class="flex items-center">
                            <img src="{{ $car->main_image }}" alt="" class="w-12 h-10 object-cover rounded-lg mr-3">
                            <div>
                                <p class="font-medium text-slate-900">{{ Str::limit($car->title, 25) }}</p>
                                <p class="text-sm text-slate-500">{{ $car->formatted_price }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-slate-400">{{ $car->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-500">No cars yet</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Inquiries -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-slate-100">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-slate-900">Recent Inquiries</h2>
                    <a href="{{ route('admin.inquiries.index') }}" class="text-sm text-amber-600 hover:text-amber-700">View All</a>
                </div>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentInquiries ?? [] as $inquiry)
                    <div class="p-4 hover:bg-slate-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-slate-900">{{ $inquiry->name }}</p>
                                <p class="text-sm text-slate-500">{{ Str::limit($inquiry->car->title ?? 'Car', 30) }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $inquiry->status === 'new' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $inquiry->status === 'contacted' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $inquiry->status === 'closed' ? 'bg-slate-100 text-slate-600' : '' }}">
                                {{ ucfirst($inquiry->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-500">No inquiries yet</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
