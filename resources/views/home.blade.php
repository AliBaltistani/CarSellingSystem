<x-layouts.public :seo="$seo ?? []">
    <!-- Hero Section with Banner Slider -->
    <section class="relative pb-10" x-data="heroSlider()">
        <!-- Banner Slider -->
        <div class="relative h-[350px] lg:h-[400px]">
            @forelse($banners as $index => $banner)
                <div class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                    :class="currentSlide === {{ $index }} ? 'opacity-100 z-10' : 'opacity-0 z-0'">
                    <!-- Banner Image -->
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" 
                        class="w-full h-full object-cover">
                    <!-- Dark Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 via-slate-900/60 to-transparent"></div>
                    
                    <!-- Banner Content -->
                    @if($banner->title || $banner->subtitle || $banner->link_url)
                    <div class="absolute inset-0 flex items-center">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                            <div class="max-w-2xl">
                                @if($banner->title)
                                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 animate-fade-in-up">
                                        {{ $banner->title }}
                                    </h2>
                                @endif
                                @if($banner->subtitle)
                                    <p class="text-lg md:text-xl text-slate-200 mb-6">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" 
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-orange-500/25">
                                        {{ $banner->link_text ?? 'Learn More' }}
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @empty
                <!-- Default Hero when no banners -->
                <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.2&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                    </div>
                    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-500/20 rounded-full blur-3xl"></div>
                </div>
            @endforelse


            <!-- Slider Arrows -->
            @if($banners->count() > 1)
            <button @click="prevSlide()" class="absolute left-4 top-1/2 transform -translate-y-1/2 z-20 p-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button @click="nextSlide()" class="absolute right-4 top-1/2 transform -translate-y-1/2 z-20 p-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            @endif
        </div>

        <!-- Centered Search Box -->
        <div class="absolute inset-0 flex items-center justify-center z-30 px-4">
            <div class="w-full max-w-5xl" x-data="carSearchForm()">
                <!-- Title -->
                <h1 class="text-center text-3xl md:text-4xl lg:text-5xl font-light text-white mb-8 italic">
                    Explore Our Selection Of Luxury Vehicles
                </h1>
                
                <!-- Search Form Container -->
                <div class="bg-white rounded-lg shadow-2xl border-2 border-blue-500">
                    <!-- Top Row: Filter Dropdowns (Make | Year | Condition) -->
                    <div class="flex flex-col md:flex-row bg-slate-50">
                        <!-- Make Dropdown -->
                        <div class="flex-1 relative border-b md:border-b-0 md:border-r border-slate-200">
                            <select x-model="selectedMake" @change="searchCars()"
                                class="w-full px-4 py-3 bg-transparent border-0 text-slate-700 appearance-none cursor-pointer focus:outline-none focus:ring-0 text-sm">
                                <option value="">Select Make</option>
                                @foreach($makes ?? [] as $make)
                                    <option value="{{ $make }}">{{ $make }}</option>
                                @endforeach
                            </select>
                            <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <!-- Year Dropdown -->
                        <div class="flex-1 relative border-b md:border-b-0 md:border-r border-slate-200">
                            <select x-model="selectedYear" @change="searchCars()"
                                class="w-full px-4 py-3 bg-transparent border-0 text-slate-700 appearance-none cursor-pointer focus:outline-none focus:ring-0 text-sm">
                                <option value="">Select Year</option>
                                @for($year = date('Y') + 1; $year >= 1990; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                            <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <!-- Condition Dropdown -->
                        <div class="flex-1 relative">
                            <select x-model="selectedCondition" @change="searchCars()"
                                class="w-full px-4 py-3 bg-transparent border-0 text-slate-700 appearance-none cursor-pointer focus:outline-none focus:ring-0 text-sm">
                                <option value="">Condition</option>
                                <option value="new">New</option>
                                <option value="used">Used</option>
                                <option value="certified">Certified Pre-Owned</option>
                            </select>
                            <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Bottom Row: Location | Search Input | Button -->
                    <div class="flex flex-col md:flex-row border-t border-slate-200">
                        <!-- Location Input -->
                        <div class="relative md:w-64 border-b md:border-b-0 md:border-r border-slate-200" @click.away="locationOpen = false">
                            <div class="flex items-center px-4 py-4">
                                <svg class="w-5 h-5 text-slate-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="3" stroke-width="2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v3m0 14v3m-7-10H2m20 0h-3m-2.636-5.364l2.122-2.122M6.514 17.486l-2.122 2.122m0-13.972l2.122 2.122m10.972 10.972l2.122 2.122"/>
                                </svg>
                                <input type="text" x-model="locationQuery" @focus="locationOpen = true" @input.debounce.300ms="searchLocations()"
                                    placeholder="Search location..."
                                    class="flex-1 bg-transparent border-0 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-0 p-0 text-sm">
                            </div>
                            
                            <!-- Location Dropdown -->
                            <div x-show="locationOpen" x-transition
                                class="absolute top-full left-0 right-0 bg-white shadow-xl border border-slate-200 z-[100] max-h-64 overflow-y-auto rounded-b-lg">
                                <button @click="getCurrentLocation()" type="button"
                                    class="w-full flex items-center px-4 py-3 hover:bg-blue-50 text-left border-b border-slate-100">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-slate-700 font-medium text-sm" x-text="gettingLocation ? 'Getting location...' : 'Use current location'"></span>
                                </button>
                                <template x-for="loc in locationResults" :key="loc.display_name">
                                    <button @click="selectLocation(loc)" type="button"
                                        class="w-full flex items-center px-4 py-3 hover:bg-slate-50 text-left">
                                        <svg class="w-4 h-4 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <span class="text-sm text-slate-700" x-text="loc.city ? (loc.city + ', ' + (loc.country || '')) : loc.display_name"></span>
                                    </button>
                                </template>
                                <div x-show="locationQuery && locationResults.length === 0 && !searchingLocation" class="px-4 py-3 text-sm text-slate-500 text-center">
                                    No locations found
                                </div>
                            </div>
                        </div>

                        <!-- Search Input with Live Suggestions -->
                        <div class="flex-1 relative" @click.away="suggestionsOpen = false">
                            <div class="flex items-center px-4 py-4">
                                <input type="text" x-model="searchQuery" 
                                    @input.debounce.300ms="searchCars()" 
                                    @focus="if(suggestions.length) suggestionsOpen = true"
                                    placeholder="Find Cars, Mobile Phones and more..."
                                    class="flex-1 bg-transparent border-0 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-0 p-0">
                            </div>
                            
                            <!-- Search Suggestions Dropdown -->
                            <div x-show="suggestionsOpen && (suggestions.length > 0 || searching)" x-transition
                                class="absolute top-full left-0 right-0 bg-white shadow-xl border border-slate-200 z-[100] max-h-80 overflow-y-auto rounded-b-lg">
                                <template x-for="car in suggestions" :key="car.id">
                                    <button @click="selectSuggestion(car)" type="button" 
                                        class="w-full flex items-center px-4 py-3 hover:bg-slate-50 border-b border-slate-100 last:border-0 text-left">
                                        <img :src="car.image" :alt="car.title" class="w-14 h-14 object-cover rounded-lg mr-4 flex-shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-slate-900 truncate" x-text="car.title"></p>
                                            <p class="text-blue-600 font-bold" x-text="car.price"></p>
                                            <p class="text-xs text-slate-500" x-text="car.category + (car.city ? ' · ' + car.city : '')"></p>
                                        </div>
                                    </button>
                                </template>
                                <div x-show="searching" class="px-4 py-3 text-sm text-slate-500 text-center">
                                    Searching...
                                </div>
                            </div>
                        </div>

                        <!-- Search Button -->
                        <button @click="submitSearch()" type="button"
                            class="px-6 py-4 bg-teal-700 hover:bg-teal-800 text-white transition-all flex items-center justify-center rounded-br-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slider Navigation Dots - moved above search -->
        @if($banners->count() > 1)
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 flex space-x-2">
            @foreach($banners as $index => $banner)
                <button @click="goToSlide({{ $index }})" 
                    class="w-3 h-3 rounded-full transition-all duration-300"
                    :class="currentSlide === {{ $index }} ? 'bg-amber-500 w-8' : 'bg-white/50 hover:bg-white/80'">
                </button>
            @endforeach
        </div>
        @endif
    </section>

    <!-- Stats Section -->
    <section class="relative z-10 mt-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">
                        {{ number_format($stats['total_cars'] ?? 500) }}+
                    </div>
                    <p class="mt-2 text-slate-600">Cars Listed</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">
                        {{ number_format($stats['happy_customers'] ?? 1000) }}+
                    </div>
                    <p class="mt-2 text-slate-600">Happy Customers</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">
                        {{ $stats['cities_covered'] ?? 10 }}+
                    </div>
                    <p class="mt-2 text-slate-600">Cities Covered</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Browse by Category</h2>
                <p class="mt-3 text-slate-600">Find your perfect match from our curated categories</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('cars.category', $category) }}" 
                        class="group relative bg-white rounded-xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-amber-200 text-center">
                        <div class="w-14 h-14 mx-auto bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900">{{ $category->name }}</h3>
                        <p class="text-sm text-slate-500 mt-1">{{ $category->cars_count }} cars</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Cars Section -->
    @if($featuredCars->count() > 0)
    <section class="py-20 bg-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">Featured Cars</h2>
                    <p class="mt-2 text-slate-600">Hand-picked premium vehicles</p>
                </div>
                <a href="{{ route('cars.index') }}" class="text-amber-600 hover:text-amber-700 font-semibold flex items-center">
                    View All
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredCars as $car)
                    <x-car-card :car="$car" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Latest Cars Section -->
    @if($latestCars->count() > 0)
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">Latest Arrivals</h2>
                    <p class="mt-2 text-slate-600">Fresh listings added recently</p>
                </div>
                <a href="{{ route('cars.index') }}" class="text-amber-600 hover:text-amber-700 font-semibold flex items-center">
                    View All
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($latestCars as $car)
                    <x-car-card :car="$car" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-slate-900 to-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white">Ready to Sell Your Car?</h2>
            <p class="mt-4 text-xl text-slate-300">
                List your car for free and connect with thousands of potential buyers
            </p>
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('cars.create') }}" class="px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-orange-500/25">
                    Sell Your Car Now
                </a>
                <a href="{{ route('cars.index') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white font-semibold rounded-xl transition-all">
                    Browse Cars
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Why Choose Xenon Motors?</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Verified Listings</h3>
                    <p class="text-slate-600">All cars are reviewed to ensure quality and accuracy</p>
                </div>
                <div class="text-center p-8">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Direct WhatsApp Chat</h3>
                    <p class="text-slate-600">Connect directly with sellers via WhatsApp instantly</p>
                </div>
                <div class="text-center p-8">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Find Nearby Cars</h3>
                    <p class="text-slate-600">Auto-detect your location to find cars near you</p>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // Hero Slider Component
        function heroSlider() {
            return {
                currentSlide: 0,
                totalSlides: {{ $banners->count() ?: 1 }},
                autoplayInterval: null,
                
                init() {
                    if (this.totalSlides > 1) {
                        this.startAutoplay();
                    }
                },
                
                startAutoplay() {
                    this.autoplayInterval = setInterval(() => {
                        this.nextSlide();
                    }, 5000);
                },
                
                stopAutoplay() {
                    if (this.autoplayInterval) {
                        clearInterval(this.autoplayInterval);
                    }
                },
                
                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },
                
                prevSlide() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                },
                
                goToSlide(index) {
                    this.currentSlide = index;
                    this.stopAutoplay();
                    this.startAutoplay();
                }
            };
        }

        // Car Search Form Component
        function carSearchForm() {
            return {
                // Location state
                locationOpen: false,
                locationQuery: '',
                locationResults: [],
                searchingLocation: false,
                gettingLocation: false,
                selectedLocation: '',
                selectedLat: null,
                selectedLon: null,
                
                // Search state
                searchQuery: '',
                suggestions: [],
                suggestionsOpen: false,
                searching: false,
                selectedMake: '',
                selectedYear: '',
                selectedCondition: '',
                selectedCarId: null,
                
                async searchCars() {
                    this.searching = true;
                    this.suggestionsOpen = true;
                    
                    try {
                        // Build query params with all filters
                        const params = new URLSearchParams();
                        
                        if (this.searchQuery && this.searchQuery.length >= 2) {
                            params.append('q', this.searchQuery);
                        }
                        if (this.selectedMake) {
                            params.append('make', this.selectedMake);
                        }
                        if (this.selectedYear) {
                            params.append('year', this.selectedYear);
                        }
                        if (this.selectedCondition) {
                            params.append('condition', this.selectedCondition);
                        }
                        if (this.locationQuery) {
                            params.append('city', this.locationQuery);
                        }
                        
                        const response = await fetch(`/api/cars/suggestions?${params.toString()}`);
                        this.suggestions = await response.json();
                    } catch (error) {
                        console.error('Car search error:', error);
                        this.suggestions = [];
                    }
                    
                    this.searching = false;
                },
                
                selectSuggestion(car) {
                    this.searchQuery = car.title;
                    this.selectedCarId = car.id;
                    this.suggestionsOpen = false;
                    this.suggestions = [];
                },
                
                async searchLocations() {
                    if (this.locationQuery.length < 2) {
                        this.locationResults = [];
                        return;
                    }
                    
                    this.searchingLocation = true;
                    try {
                        const response = await fetch(`/api/locations/search?q=${encodeURIComponent(this.locationQuery)}`);
                        this.locationResults = await response.json();
                    } catch (error) {
                        console.error('Location search error:', error);
                        this.locationResults = [];
                    }
                    this.searchingLocation = false;
                },
                
                selectLocation(loc) {
                    const displayName = loc.city ? `${loc.city}, ${loc.country || ''}` : loc.name || loc.display_name;
                    this.locationQuery = displayName;
                    this.selectedLocation = displayName;
                    this.selectedLat = loc.lat;
                    this.selectedLon = loc.lon;
                    this.locationOpen = false;
                    this.locationResults = [];
                    
                    // Trigger search to update suggestions based on new location
                    this.searchCars();
                    
                    localStorage.setItem('selectedLocation', displayName);
                    localStorage.setItem('selectedLat', loc.lat);
                    localStorage.setItem('selectedLon', loc.lon);
                },
                
                async getCurrentLocation() {
                    if (!navigator.geolocation) {
                        alert('Geolocation is not supported by your browser');
                        return;
                    }
                    
                    this.gettingLocation = true;
                    
                    navigator.geolocation.getCurrentPosition(
                        async (position) => {
                            const { latitude, longitude } = position.coords;
                            
                            try {
                                const response = await fetch(`/api/locations/reverse?lat=${latitude}&lon=${longitude}`);
                                const data = await response.json();
                                
                                if (data.city || data.display_name) {
                                    this.selectLocation({
                                        city: data.city,
                                        country: data.country,
                                        display_name: data.display_name,
                                        lat: latitude,
                                        lon: longitude
                                    });
                                }
                            } catch (error) {
                                console.error('Reverse geocoding error:', error);
                            }
                            
                            this.gettingLocation = false;
                        },
                        (error) => {
                            console.error('Geolocation error:', error);
                            this.gettingLocation = false;
                            alert('Unable to get your location. Please enable location services.');
                        }
                    );
                },
                
                submitSearch() {
                    const params = new URLSearchParams();
                    
                    if (this.searchQuery) {
                        params.append('search', this.searchQuery);
                    }
                    
                    if (this.selectedLat && this.selectedLon) {
                        params.append('latitude', this.selectedLat);
                        params.append('longitude', this.selectedLon);
                    }
                    
                    if (this.selectedMake) {
                        params.append('make', this.selectedMake);
                    }
                    
                    if (this.selectedYear) {
                        params.append('year', this.selectedYear);
                    }
                    
                    if (this.selectedCondition) {
                        params.append('condition', this.selectedCondition);
                    }
                    
                    window.location.href = '/cars?' + params.toString();
                }
            };
        }
    </script>
    @endpush
</x-layouts.public>

