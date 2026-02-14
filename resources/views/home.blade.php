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
                <div class="bg-white rounded-lg shadow-2xl ">
                    <!-- Top Row: Filter Dropdowns (Make | Year | Condition) -->
                    <div class="flex flex-col md:flex-row bg-slate-50">
                        <!-- Make Dropdown - Searchable -->
                        <div class="flex-1 relative border-b md:border-b-0 md:border-r border-slate-200" 
                             x-data="{ open: false, search: '' }"
                             @click.away="open = false">
                            <button type="button" @click="open = !open" 
                                class="w-full px-4 py-3 bg-transparent border-0 text-slate-700 cursor-pointer focus:outline-none focus:ring-0 text-sm text-left flex items-center justify-between">
                                <span x-text="selectedMake || 'Select Make'" :class="selectedMake ? 'text-slate-700' : 'text-slate-500'"></span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition 
                                 class="absolute top-full left-0 right-0 bg-white shadow-xl border border-slate-200 z-[100] rounded-b-lg overflow-hidden">
                                <div class="p-2 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search make..." 
                                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <button type="button" @click="selectedMake = ''; selectedMakeId = null; models = []; selectedModel = ''; search = ''; open = false; searchCars()" 
                                            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">
                                        All Makes
                                    </button>
                                    @foreach($makes ?? [] as $make)
                                    <button type="button" 
                                            x-show="!search || '{{ strtolower($make->label) }}'.includes(search.toLowerCase())"
                                            @click="selectedMake = '{{ $make->value }}'; selectedMakeId = '{{ $make->id }}'; fetchModels(); search = ''; open = false; searchCars()" 
                                            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-700"
                                            :class="selectedMake === '{{ $make->value }}' ? 'bg-teal-50 text-teal-700' : ''">
                                        {{ $make->label }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Model Dropdown - Searchable (Dynamic) -->
                        <div class="flex-1 relative border-b md:border-b-0 md:border-r border-slate-200" 
                             x-data="{ open: false, search: '' }"
                             @click.away="open = false">
                            <button type="button" @click="if(selectedMake) open = !open" 
                                class="w-full px-4 py-3 bg-transparent border-0 text-slate-700 cursor-pointer focus:outline-none focus:ring-0 text-sm text-left flex items-center justify-between"
                                :class="!selectedMake ? 'cursor-not-allowed opacity-60' : ''">
                                <span x-text="selectedModel || 'Select Model'" :class="selectedModel ? 'text-slate-700' : 'text-slate-500'"></span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open && selectedMake" x-transition 
                                 class="absolute top-full left-0 right-0 bg-white shadow-xl border border-slate-200 z-[100] rounded-b-lg overflow-hidden">
                                <div class="p-2 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search model..." 
                                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <button type="button" @click="selectedModel = ''; search = ''; open = false; searchCars()" 
                                            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">
                                        All Models
                                    </button>
                                    <template x-for="model in models" :key="model.id">
                                        <button type="button" 
                                                x-show="!search || model.label.toLowerCase().includes(search.toLowerCase())"
                                                @click="selectedModel = model.label; search = ''; open = false; searchCars()" 
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-700"
                                                :class="selectedModel === model.label ? 'bg-teal-50 text-teal-700' : ''"
                                                x-text="model.label">
                                        </button>
                                    </template>
                                    <div x-show="models.length === 0" class="px-4 py-2 text-sm text-slate-500 italic">
                                        No models found
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Year Dropdown - Searchable -->
                        <div class="flex-1 relative border-b md:border-b-0 md:border-r border-slate-200" 
                             x-data="{ open: false, search: '' }"
                             @click.away="open = false">
                            <button type="button" @click="open = !open" 
                                class="w-full px-4 py-3 bg-transparent border-0 text-slate-700 cursor-pointer focus:outline-none focus:ring-0 text-sm text-left flex items-center justify-between">
                                <span x-text="selectedYear || 'Select Year'" :class="selectedYear ? 'text-slate-700' : 'text-slate-500'"></span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition 
                                 class="absolute top-full left-0 right-0 bg-white shadow-xl border border-slate-200 z-[100] rounded-b-lg overflow-hidden">
                                <div class="p-2 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search year..." 
                                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <button type="button" @click="selectedYear = ''; search = ''; open = false; searchCars()" 
                                            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">
                                        All Years
                                    </button>
                                    @foreach($years ?? [] as $year)
                                    <button type="button" 
                                            x-show="!search || '{{ $year }}'.includes(search)"
                                            @click="selectedYear = '{{ $year }}'; search = ''; open = false; searchCars()" 
                                            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-700"
                                            :class="selectedYear == '{{ $year }}' ? 'bg-teal-50 text-teal-700' : ''">
                                        {{ $year }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Condition Dropdown - Searchable -->
                        <div class="flex-1 relative" 
                             x-data="{ open: false, search: '' }"
                             @click.away="open = false">
                            <button type="button" @click="open = !open" 
                                class="w-full px-4 py-3 bg-transparent border-0 text-slate-700 cursor-pointer focus:outline-none focus:ring-0 text-sm text-left flex items-center justify-between">
                                <span x-text="conditionLabels[selectedCondition] || 'Condition'" :class="selectedCondition ? 'text-slate-700' : 'text-slate-500'"></span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition 
                                 class="absolute top-full left-0 right-0 bg-white shadow-xl border border-slate-200 z-[100] rounded-b-lg overflow-hidden">
                                <div class="p-2 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search condition..." 
                                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <button type="button" @click="selectedCondition = ''; search = ''; open = false; searchCars()" 
                                            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">
                                        All Conditions
                                    </button>
                                    @foreach($conditions ?? [] as $condition)
                                    <button type="button" 
                                            x-show="!search || '{{ strtolower($condition->label) }}'.includes(search.toLowerCase())"
                                            @click="selectedCondition = '{{ $condition->value }}'; search = ''; open = false; searchCars()" 
                                            class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-700"
                                            :class="selectedCondition === '{{ $condition->value }}' ? 'bg-teal-50 text-teal-700' : ''">
                                        {{ $condition->label }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Row: Location | Search Input | Button -->
                    <div class="flex flex-col md:flex-row border-t border-slate-200">
                        <!-- Location Input -->
                        <div class="relative md:w-64 border-b md:border-b-0 md:border-r border-slate-200"
                             @location-selected.window="selectedCity = $event.detail.city; selectedLat = $event.detail.lat; selectedLon = $event.detail.lon"
                             @location-cleared.window="selectedCity = ''; selectedLat = null; selectedLon = null">
                            <x-forms.location-search 
                                name="location" 
                                placeholder="Search location..." 
                                class="w-full"
                            />
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
        <div class="absolute bottom-8 mb-4 left-1/2 transform -translate-x-1/2 z-20 flex space-x-2">
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
                <h2 class="text-3xl font-bold text-slate-900">Why Choose {{ $globalSettings['site_name'] ?? '' }}?</h2>
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

    <!-- Testimonials Section -->
    @if(isset($testimonials) && $testimonials->count() > 0)
    <section class="py-20 bg-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Happy Customers</h2>
                <p class="mt-3 text-slate-600">What our customers say about us</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                    <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition-shadow">
                        <!-- Rating Stars -->
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-amber-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        
                        <!-- Review Text -->
                        <p class="text-slate-600 mb-6 line-clamp-4">
                            "{{ $testimonial->review }}"
                        </p>
                        
                        <!-- Customer Info -->
                        <div class="flex items-center">
                            <img src="{{ $testimonial->photo_url }}" alt="{{ $testimonial->customer_name }}" 
                                class="w-12 h-12 rounded-full object-cover mr-4">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $testimonial->customer_name }}</div>
                                @if($testimonial->designation)
                                    <div class="text-sm text-slate-500">{{ $testimonial->designation }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Financing Partners Section -->
    @if($financingPartners->count() > 0)
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-slate-900 mb-3">Our Financing Partners</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Trusted banks and financial institutions to help you drive your dream car home</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach($financingPartners as $partner)
                    <a href="{{ $partner->website_url ?: '#' }}" 
                       target="{{ $partner->website_url ? '_blank' : '_self' }}"
                       class="group bg-slate-50 hover:bg-white rounded-xl p-6 flex items-center justify-center transition-all hover:shadow-lg border border-slate-100"
                       title="{{ $partner->name }}">
                        <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" 
                             class="max-h-12 max-w-full object-contain grayscale group-hover:grayscale-0 transition-all">
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Our Offers Section -->
    @if($offers->count() > 0)
    <section class="py-20 bg-gradient-to-br from-slate-900 via-slate-800 to-amber-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1 bg-amber-500/20 text-amber-400 text-sm font-semibold rounded-full mb-4">Special Offers</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Exclusive Deals & Packages</h2>
                <p class="text-slate-300 max-w-2xl mx-auto">Take advantage of our limited-time offers and service packages</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($offers as $offer)
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl overflow-hidden border border-white/10 hover:border-amber-500/50 transition-all group">
                        <!-- Badge -->
                        @if($offer->badge)
                            <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-bold text-center py-2 uppercase tracking-wider">
                                {{ $offer->badge }}
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <!-- Title & Icon -->
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ $offer->title }}</h3>
                                    @if($offer->price_label)
                                        <span class="text-amber-400 text-sm font-medium">{{ $offer->price_label }}</span>
                                    @endif
                                </div>
                                @if($offer->icon_url)
                                    <img src="{{ $offer->icon_url }}" alt="" class="w-12 h-12 object-contain">
                                @endif
                            </div>
                            
                            <!-- Description -->
                            @if($offer->description)
                                <p class="text-slate-300 text-sm mb-4">{{ Str::limit($offer->description, 120) }}</p>
                            @endif
                            
                            <!-- Features -->
                            @if($offer->features && count($offer->features) > 0)
                                <ul class="space-y-2 mb-6">
                                    @foreach(array_slice($offer->features, 0, 4) as $feature)
                                        <li class="flex items-center text-sm text-slate-300">
                                            <svg class="w-4 h-4 text-emerald-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            
                            <!-- Pricing -->
                            @if($offer->price_from)
                                <div class="flex items-baseline gap-2 mb-4">
                                    <span class="text-slate-400 text-sm">From</span>
                                    <span class="text-2xl font-bold text-white">AED {{ number_format($offer->price_from) }}</span>
                                </div>
                            @endif
                            
                            <!-- CTA -->
                            <a href="{{ route('offers.show', $offer) }}" 
                               class="block w-full text-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all group-hover:shadow-lg group-hover:shadow-amber-500/25">
                                {{ $offer->cta_text ?? 'View Deal' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

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
                // Location state
                selectedCity: '', // Kept for searchCars usage
                selectedLat: null,
                selectedLon: null,
                
                // Search state
                searchQuery: '',
                suggestions: [],
                suggestionsOpen: false,
                searching: false,
                selectedMake: '',
                selectedMakeId: null, // Added for API call
                selectedModel: '', // Added for Model selection
                models: [], // Added for Model list
                selectedYear: '',
                selectedCondition: '',
                selectedCarId: null,
                
                // Condition labels mapping for display
                conditionLabels: {
                    @foreach($conditions ?? [] as $condition)
                    '{{ $condition->value }}': '{{ $condition->label }}',
                    @endforeach
                },
                
                async fetchModels() {
                    this.models = [];
                    this.selectedModel = ''; // Reset model when make changes
                    
                    if (!this.selectedMakeId) {
                        return;
                    }

                    try {
                        const response = await fetch(`/api/attributes/models?make_id=${this.selectedMakeId}`);
                        if (response.ok) {
                            this.models = await response.json();
                        }
                    } catch (error) {
                        console.error('Failed to fetch models:', error);
                    }
                },

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
                        if (this.selectedModel) {
                            params.append('model', this.selectedModel);
                        }
                        if (this.selectedYear) {
                            params.append('year', this.selectedYear);
                        }
                        if (this.selectedCondition) {
                            params.append('condition', this.selectedCondition);
                        }
                        if (this.selectedCity || this.locationQuery) {
                            // Send just the city name if available, otherwise send the full query
                            params.append('city', this.selectedCity || this.locationQuery.split(',')[0].trim());
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
                

                
                submitSearch() {
                    const params = new URLSearchParams();
                    
                    if (this.searchQuery) {
                        params.append('search', this.searchQuery);
                    }
                    
                    if (this.selectedLat && this.selectedLon) {
                        params.append('latitude', this.selectedLat);
                        params.append('longitude', this.selectedLon);
                    }
                    
                    if (this.selectedCity) {
                        params.append('city', this.selectedCity);
                    }
                    
                    if (this.selectedMake) {
                        params.append('make', this.selectedMake);
                    }
                    
                    if (this.selectedModel) {
                        params.append('model', this.selectedModel);
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

