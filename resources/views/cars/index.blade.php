<x-layouts.public :seo="$seo ?? []">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">
                @if(isset($category))
                    {{ $category->name }} Cars
                @elseif(isset($searchQuery))
                    Search Results for "{{ $searchQuery }}"
                @else
                    Browse All Cars
                @endif
            </h1>
            <p class="text-slate-600 mt-2">
                {{ $cars->total() }} cars found
            </p>
        </div>

        <div class="lg:grid lg:grid-cols-4 lg:gap-8">
            <!-- Filters Sidebar -->
            <aside class="lg:col-span-1">
                <form action="{{ route('cars.index') }}" method="GET" class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Filters</h2>

                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Make, model, keyword..."
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>

                    <!-- Make -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Make</label>
                        <select name="make" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="">All Makes</option>
                            @foreach($makes ?? [] as $make)
                                <option value="{{ $make }}" {{ request('make') == $make ? 'selected' : '' }}>{{ $make }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Price Range (AED)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <!-- Year Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Year</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_year" value="{{ request('min_year') }}" placeholder="From"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                            <input type="number" name="max_year" value="{{ request('max_year') }}" placeholder="To"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <!-- Condition -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Condition</label>
                        <select name="condition" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            <option value="">All Conditions</option>
                            <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>Used</option>
                            <option value="certified" {{ request('condition') == 'certified' ? 'selected' : '' }}>Certified Pre-Owned</option>
                        </select>
                    </div>

                    <!-- Transmission -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Transmission</label>
                        <select name="transmission" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            <option value="">All Types</option>
                            <option value="automatic" {{ request('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="cvt" {{ request('transmission') == 'cvt' ? 'selected' : '' }}>CVT</option>
                        </select>
                    </div>

                    <!-- Fuel Type -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Fuel Type</label>
                        <select name="fuel_type" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            <option value="">All Fuel Types</option>
                            <option value="petrol" {{ request('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
                            <option value="diesel" {{ request('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="electric" {{ request('fuel_type') == 'electric' ? 'selected' : '' }}>Electric</option>
                            <option value="hybrid" {{ request('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-lg transition-all">
                            Apply Filters
                        </button>
                        <a href="{{ route('cars.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                            Clear
                        </a>
                    </div>
                </form>
            </aside>

            <!-- Car Grid -->
            <div class="lg:col-span-3 mt-8 lg:mt-0">
                @if($cars->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($cars as $car)
                            <x-car-card :car="$car" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $cars->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-white rounded-xl">
                        <svg class="w-16 h-16 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-4 text-xl font-semibold text-slate-900">No cars found</h3>
                        <p class="mt-2 text-slate-600">Try adjusting your filters or search criteria</p>
                        <a href="{{ route('cars.index') }}" class="inline-block mt-6 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors">
                            Clear All Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.public>
