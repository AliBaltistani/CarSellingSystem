<x-layouts.public :seo="$seo ?? []">
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
        [x-cloak] { display: none !important; }
        .filter-sidebar { background: rgba(255,255,255,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
        .filter-chip { @apply inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 text-sm font-medium rounded-full border border-amber-200 transition-all hover:bg-amber-100; }
    </style>

    <div x-data="carListing()" x-init="init()" x-cloak class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                <span x-text="totalCount + ' cars found'" x-show="!loading || cars.length > 0"></span>
                <span x-show="loading && cars.length === 0" class="inline-flex items-center gap-2"><svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>Searching...</span>
            </p>
        </div>

        <!-- Active Filter Chips -->
        <div x-show="hasActiveFilters()" x-transition class="flex flex-wrap gap-2 mb-6">
            <template x-if="filters.search"><span class="filter-chip">Search: <span x-text="filters.search"></span><button @click="filters.search = ''; applyFilters()" class="ml-1 text-amber-500 hover:text-amber-700">&times;</button></span></template>
            <template x-if="filters.make"><span class="filter-chip">Make: <span x-text="filters.make"></span><button @click="filters.make = ''; applyFilters()" class="ml-1 text-amber-500 hover:text-amber-700">&times;</button></span></template>
            <template x-if="filters.condition"><span class="filter-chip">Condition: <span x-text="labelMaps.condition[filters.condition] || filters.condition"></span><button @click="filters.condition = ''; applyFilters()" class="ml-1 text-amber-500 hover:text-amber-700">&times;</button></span></template>
            <template x-if="filters.transmission"><span class="filter-chip">Trans: <span x-text="labelMaps.transmission[filters.transmission] || filters.transmission"></span><button @click="filters.transmission = ''; applyFilters()" class="ml-1 text-amber-500 hover:text-amber-700">&times;</button></span></template>
            <template x-if="filters.fuel_type"><span class="filter-chip">Fuel: <span x-text="labelMaps.fuel_type[filters.fuel_type] || filters.fuel_type"></span><button @click="filters.fuel_type = ''; applyFilters()" class="ml-1 text-amber-500 hover:text-amber-700">&times;</button></span></template>
            <template x-if="filters.category"><span class="filter-chip">Category: <span x-text="labelMaps.category[filters.category] || filters.category"></span><button @click="filters.category = ''; applyFilters()" class="ml-1 text-amber-500 hover:text-amber-700">&times;</button></span></template>
            <template x-if="filters.city"><span class="filter-chip">Location: <span x-text="filters.city"></span><button @click="filters.city = ''; filters.latitude = ''; filters.longitude = ''; applyFilters()" class="ml-1 text-amber-500 hover:text-amber-700">&times;</button></span></template>
            <template x-if="filters.min_price || filters.max_price"><span class="filter-chip">Price: <span x-text="(filters.min_price || '0') + ' - ' + (filters.max_price || '∞')"></span><button @click="filters.min_price = ''; filters.max_price = ''; applyFilters()" class="ml-1 text-amber-500 hover:text-amber-700">&times;</button></span></template>
            <button @click="clearAllFilters()" class="text-sm text-red-500 hover:text-red-600 font-medium underline underline-offset-2">Clear All</button>
        </div>

        <div class="lg:grid lg:grid-cols-4 lg:gap-8">
            <!-- Mobile Filter Toggle -->
            <button @click="showMobileFilters = true" class="lg:hidden mb-4 w-full flex items-center justify-center gap-2 px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-700 font-medium shadow-sm hover:shadow-md transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filters <span x-show="activeFilterCount > 0" class="px-2 py-0.5 bg-amber-500 text-white text-xs rounded-full" x-text="activeFilterCount"></span>
            </button>

            <!-- Filter Sidebar (Desktop) -->
            <aside class="hidden lg:block lg:col-span-1">
                <div class="filter-sidebar rounded-2xl shadow-sm p-6 sticky top-24 border border-slate-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-slate-900">Filters</h2>
                        <button @click="clearAllFilters()" x-show="hasActiveFilters()" class="text-xs text-red-500 hover:text-red-600 font-medium">Clear All</button>
                    </div>
                    <template x-ref="filterControls">
                    </template>
                    <!-- Search -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Search</label>
                        <input type="text" x-model="filters.search" @input.debounce.500ms="applyFilters()" placeholder="Make, model, keyword..." class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-white text-sm">
                    </div>

                    <!-- Location -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Location</label>
                        <div class="relative" x-data="filterLocationSearch()">
                            <input type="text" x-model="locQuery" @input.debounce.400ms="searchLocation()" @focus="if(locResults.length) locDropOpen=true" placeholder="Search location..." class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-white text-sm pr-8">
                            <button x-show="locQuery" @click="locQuery=''; $root.__x.$data.filters.city=''; $root.__x.$data.filters.latitude=''; $root.__x.$data.filters.longitude=''; $root.__x.$data.applyFilters()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">&times;</button>
                            <div x-show="locDropOpen" @click.away="locDropOpen=false" x-transition class="absolute top-full left-0 right-0 bg-white shadow-xl border border-slate-200 rounded-xl mt-1 z-50 max-h-48 overflow-y-auto">
                                <template x-for="loc in locResults" :key="loc.display_name || loc.name">
                                    <button type="button" @click="selectLocation(loc)" class="w-full px-4 py-2.5 text-left text-sm hover:bg-slate-50 border-b border-slate-50 last:border-0" x-text="loc.display_name || loc.name"></button>
                                </template>
                            </div>
                            <button type="button" @click="detectLocation()" class="mt-2 flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span x-text="gettingLoc ? 'Getting location...' : 'Use my location'"></span>
                            </button>
                        </div>

                    <!-- Category -->
                    <div class="mb-5" x-data="{ open: false, q: '' }" @click.away="open=false">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Category</label>
                        <button type="button" @click="open=!open" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-left flex items-center justify-between bg-white text-sm">
                            <span x-text="filters.category ? (labelMaps.category[filters.category] || filters.category) : 'All Categories'" :class="filters.category ? 'text-slate-900' : 'text-slate-500'"></span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute left-6 right-6 bg-white shadow-xl border border-slate-200 rounded-xl mt-1 z-50 max-h-64 overflow-hidden">
                            <div class="p-2 border-b border-slate-100"><input type="text" x-model="q" placeholder="Search..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"></div>
                            <div class="max-h-48 overflow-y-auto">
                                <button type="button" @click="filters.category=''; open=false; applyFilters()" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">All Categories</button>
                                @foreach($categories ?? [] as $cat)
                                <button type="button" x-show="!q || '{{ strtolower($cat->name) }}'.includes(q.toLowerCase())" @click="filters.category='{{ $cat->slug }}'; open=false; applyFilters()" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50" :class="filters.category==='{{ $cat->slug }}'?'bg-amber-50 text-amber-700':'text-slate-700'">{{ $cat->name }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Make -->
                    <div class="mb-5" x-data="{ open: false, q: '' }" @click.away="open=false">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Make</label>
                        <button type="button" @click="open=!open" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-left flex items-center justify-between bg-white text-sm">
                            <span x-text="filters.make || 'All Makes'" :class="filters.make ? 'text-slate-900' : 'text-slate-500'"></span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute left-6 right-6 bg-white shadow-xl border border-slate-200 rounded-xl mt-1 z-50 max-h-64 overflow-hidden">
                            <div class="p-2 border-b border-slate-100"><input type="text" x-model="q" placeholder="Search..." class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"></div>
                            <div class="max-h-48 overflow-y-auto">
                                <button type="button" @click="filters.make=''; open=false; applyFilters()" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">All Makes</button>
                                @foreach($makes ?? [] as $make)
                                <button type="button" x-show="!q || '{{ strtolower($make->label) }}'.includes(q.toLowerCase())" @click="filters.make='{{ $make->value }}'; open=false; applyFilters()" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50" :class="filters.make==='{{ $make->value }}'?'bg-amber-50 text-amber-700':'text-slate-700'">{{ $make->label }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Price Range (AED)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" x-model="filters.min_price" @input.debounce.700ms="applyFilters()" placeholder="Min" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                            <input type="number" x-model="filters.max_price" @input.debounce.700ms="applyFilters()" placeholder="Max" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <!-- Year Range -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Year</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" x-model="filters.min_year" @input.debounce.700ms="applyFilters()" placeholder="From" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                            <input type="number" x-model="filters.max_year" @input.debounce.700ms="applyFilters()" placeholder="To" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <!-- Condition -->
                    <div class="mb-5" x-data="{ open: false }" @click.away="open=false">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Condition</label>
                        <button type="button" @click="open=!open" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-left flex items-center justify-between bg-white text-sm">
                            <span x-text="filters.condition ? (labelMaps.condition[filters.condition] || filters.condition) : 'All Conditions'" :class="filters.condition ? 'text-slate-900' : 'text-slate-500'"></span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute left-6 right-6 bg-white shadow-xl border border-slate-200 rounded-xl mt-1 z-50">
                            <button type="button" @click="filters.condition=''; open=false; applyFilters()" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">All Conditions</button>
                            @foreach($conditions ?? [] as $option)
                            <button type="button" @click="filters.condition='{{ $option->value }}'; open=false; applyFilters()" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50" :class="filters.condition==='{{ $option->value }}'?'bg-amber-50 text-amber-700':'text-slate-700'">{{ $option->label }}</button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Transmission -->
                    <div class="mb-5" x-data="{ open: false }" @click.away="open=false">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Transmission</label>
                        <button type="button" @click="open=!open" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-left flex items-center justify-between bg-white text-sm">
                            <span x-text="filters.transmission ? (labelMaps.transmission[filters.transmission] || filters.transmission) : 'All Types'" :class="filters.transmission ? 'text-slate-900' : 'text-slate-500'"></span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute left-6 right-6 bg-white shadow-xl border border-slate-200 rounded-xl mt-1 z-50">
                            <button type="button" @click="filters.transmission=''; open=false; applyFilters()" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">All Types</button>
                            @foreach($transmissions ?? [] as $option)
                            <button type="button" @click="filters.transmission='{{ $option->value }}'; open=false; applyFilters()" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50" :class="filters.transmission==='{{ $option->value }}'?'bg-amber-50 text-amber-700':'text-slate-700'">{{ $option->label }}</button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fuel Type -->
                    <div class="mb-5" x-data="{ open: false }" @click.away="open=false">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Fuel Type</label>
                        <button type="button" @click="open=!open" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-left flex items-center justify-between bg-white text-sm">
                            <span x-text="filters.fuel_type ? (labelMaps.fuel_type[filters.fuel_type] || filters.fuel_type) : 'All Fuel Types'" :class="filters.fuel_type ? 'text-slate-900' : 'text-slate-500'"></span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute left-6 right-6 bg-white shadow-xl border border-slate-200 rounded-xl mt-1 z-50">
                            <button type="button" @click="filters.fuel_type=''; open=false; applyFilters()" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">All Fuel Types</button>
                            @foreach($fuelTypes ?? [] as $option)
                            <button type="button" @click="filters.fuel_type='{{ $option->value }}'; open=false; applyFilters()" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50" :class="filters.fuel_type==='{{ $option->value }}'?'bg-amber-50 text-amber-700':'text-slate-700'">{{ $option->label }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Mobile Filter Drawer -->
            <div x-show="showMobileFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-50 lg:hidden" @click.self="showMobileFilters=false">
                <div x-show="showMobileFilters" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="absolute right-0 top-0 bottom-0 w-80 bg-white shadow-2xl overflow-y-auto p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-slate-900">Filters</h2>
                        <button @click="showMobileFilters=false" class="p-2 hover:bg-slate-100 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <!-- Mobile filters mirror desktop filters inline -->
                    <div class="mb-5"><label class="block text-sm font-medium text-slate-700 mb-1.5">Search</label><input type="text" x-model="filters.search" @input.debounce.500ms="applyFilters()" placeholder="Make, model..." class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500"></div>
                    <div class="mb-5"><label class="block text-sm font-medium text-slate-700 mb-1.5">Price Range</label><div class="grid grid-cols-2 gap-2"><input type="number" x-model="filters.min_price" @input.debounce.700ms="applyFilters()" placeholder="Min" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm"><input type="number" x-model="filters.max_price" @input.debounce.700ms="applyFilters()" placeholder="Max" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm"></div></div>
                    <div class="mb-5"><label class="block text-sm font-medium text-slate-700 mb-1.5">Year</label><div class="grid grid-cols-2 gap-2"><input type="number" x-model="filters.min_year" @input.debounce.700ms="applyFilters()" placeholder="From" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm"><input type="number" x-model="filters.max_year" @input.debounce.700ms="applyFilters()" placeholder="To" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm"></div></div>
                    <div class="flex gap-2 mt-6">
                        <button @click="showMobileFilters=false" class="flex-1 px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl">Show Results</button>
                        <button @click="clearAllFilters(); showMobileFilters=false" class="px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl">Clear</button>
                    </div>
                </div>
            </div>

            <!-- Car Grid -->
            <div class="lg:col-span-3 mt-8 lg:mt-0">
                <!-- Loading Overlay for filter changes -->
                <div x-show="loading && cars.length > 0" class="relative">
                    <div class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 flex items-start justify-center pt-24 rounded-xl">
                        <div class="flex items-center gap-3 px-6 py-3 bg-white shadow-lg rounded-full">
                            <svg class="animate-spin w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            <span class="text-sm font-medium text-slate-700">Updating results...</span>
                        </div>
                    </div>
                </div>

                <!-- Skeleton Loader (initial load) -->
                <div x-show="loading && cars.length === 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <template x-for="i in 9" :key="'skel-'+i"><div class="bg-white rounded-2xl shadow-sm overflow-hidden animate-pulse"><div class="aspect-[4/3] bg-slate-200"></div><div class="p-5 space-y-3"><div class="h-5 bg-slate-200 rounded w-3/4"></div><div class="h-4 bg-slate-200 rounded w-1/2"></div><div class="grid grid-cols-3 gap-3 py-3"><div class="h-8 bg-slate-200 rounded"></div><div class="h-8 bg-slate-200 rounded"></div><div class="h-8 bg-slate-200 rounded"></div></div></div></div></template>
                </div>

                <!-- Car Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" x-show="cars.length > 0 || !loading">
                    <template x-for="(car, idx) in cars" :key="'car-'+car.id">
                        <article class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100" :style="'animation-delay:' + (idx % 9 * 60) + 'ms'" x-intersect.once="$el.classList.add('animate-fade-in-up')">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <a :href="car.url"><img :src="car.image" :alt="car.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy"></a>
                                <div x-show="car.is_featured" class="absolute top-3 left-3 px-3 py-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-semibold rounded-full">Featured</div>
                                <div class="absolute top-3 right-3 px-3 py-1 bg-slate-900/70 backdrop-blur-sm text-white text-xs font-medium rounded-full" x-text="car.condition"></div>
                                <template x-if="car.distance_km"><div class="absolute bottom-3 left-3 px-2.5 py-1 bg-blue-600/90 backdrop-blur-sm text-white text-xs font-medium rounded-full flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg><span x-text="car.distance_km + ' km'"></span></div></template>
                                <!-- Favorite Button -->
                                <button @click.prevent="toggleFavorite(car.id, $event.currentTarget)" class="absolute bottom-3 right-3 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg hover:bg-white transition-colors" title="Save to Favorites">
                                    <svg class="w-5 h-5" :class="car.is_favorited ? 'text-red-500 fill-current' : 'text-slate-400'" :fill="car.is_favorited ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1 min-w-0"><h3 class="font-semibold text-slate-900 truncate group-hover:text-amber-600 transition-colors"><a :href="car.url" x-text="car.title"></a></h3><p class="text-sm text-slate-500 truncate" x-text="car.year + ' • ' + (car.category || '')"></p></div>
                                    <div class="text-right ml-4"><span class="text-lg font-bold text-amber-600" x-text="car.price"></span><p x-show="car.negotiable" class="text-xs text-slate-500">Negotiable</p></div>
                                </div>
                                <div class="grid grid-cols-3 gap-3 py-3 border-y border-slate-100"><div class="text-center"><div class="text-sm font-medium text-slate-900" x-text="car.mileage || '-'"></div><div class="text-xs text-slate-500">KM</div></div><div class="text-center border-x border-slate-100"><div class="text-sm font-medium text-slate-900 capitalize" x-text="car.transmission || '-'"></div><div class="text-xs text-slate-500">Trans</div></div><div class="text-center"><div class="text-sm font-medium text-slate-900 capitalize" x-text="car.fuel_type || '-'"></div><div class="text-xs text-slate-500">Fuel</div></div></div>
                                <!-- Dynamic Attributes -->
                                <template x-if="car.card_attributes && car.card_attributes.length > 0">
                                    <div class="flex flex-wrap gap-1.5 pt-3">
                                        <template x-for="attr in car.card_attributes" :key="attr.name">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg border border-amber-100">
                                                <span class="text-amber-400" x-show="attr.icon" x-html="attr.icon"></span>
                                                <span class="text-amber-500/70" x-text="attr.name + ':'"></span>
                                                <span x-text="attr.value"></span>
                                            </span>
                                        </template>
                                    </div>
                                </template>
                                <div class="flex justify-between items-center mt-4">
                                    <div class="flex items-center text-sm text-slate-500"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span x-text="car.city"></span></div>
                                    <a :href="car.whatsapp_link" target="_blank" class="flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>WhatsApp
                                    </a>
                                </div>
                            </div>
                        </article>
                    </template>
                </div>

                <!-- No Results -->
                <div x-show="!loading && cars.length === 0" class="text-center py-16 bg-white rounded-2xl border border-slate-100">
                    <svg class="w-16 h-16 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <h3 class="mt-4 text-xl font-semibold text-slate-900">No cars found</h3>
                    <p class="mt-2 text-slate-600">Try adjusting your filters or search criteria</p>
                    <button @click="clearAllFilters()" class="inline-block mt-6 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-colors">Clear All Filters</button>
                </div>

                <!-- Load More -->
                <div class="text-center mt-10" x-show="hasMore && cars.length > 0">
                    <button @click="loadMore()" :disabled="loadingMore" class="px-10 py-3.5 bg-gradient-to-r from-slate-700 to-slate-900 hover:from-slate-800 hover:to-slate-950 disabled:opacity-50 text-white font-semibold rounded-xl transition-all shadow-lg">
                        <span x-show="!loadingMore">Load More Cars</span>
                        <span x-show="loadingMore" class="flex items-center gap-2"><svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>Loading...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function filterLocationSearch() {
            return {
                locQuery: '{{ request("city", "") }}', locResults: [], locDropOpen: false, gettingLoc: false,
                async searchLocation() {
                    if (!this.locQuery || this.locQuery.length < 2) { this.locResults = []; return; }
                    try {
                        const r = await fetch('/api/locations/combined?q=' + encodeURIComponent(this.locQuery));
                        this.locResults = await r.json();
                        this.locDropOpen = this.locResults.length > 0;
                    } catch(e) { this.locResults = []; }
                },
                selectLocation(loc) {
                    this.locQuery = loc.display_name || loc.name;
                    this.locDropOpen = false;
                    // Access parent Alpine component
                    const root = this.$el.closest('[x-data]');
                    const listing = Alpine.$data(root.closest('[x-data="carListing()"]'));
                    listing.filters.city = loc.city || loc.name;
                    listing.filters.latitude = loc.lat || '';
                    listing.filters.longitude = loc.lon || loc.lng || '';
                    listing.applyFilters();
                },
                async detectLocation() {
                    if (!navigator.geolocation) return;
                    this.gettingLoc = true;
                    navigator.geolocation.getCurrentPosition(async (pos) => {
                        const root = this.$el.closest('[x-data="carListing()"]');
                        const listing = Alpine.$data(root);
                        listing.filters.latitude = pos.coords.latitude;
                        listing.filters.longitude = pos.coords.longitude;
                        try {
                            const r = await fetch('/api/locations/reverse?lat=' + pos.coords.latitude + '&lon=' + pos.coords.longitude);
                            const d = await r.json();
                            if (d.city) { listing.filters.city = d.city; this.locQuery = d.city; }
                        } catch(e) {}
                        listing.applyFilters();
                        this.gettingLoc = false;
                    }, () => { this.gettingLoc = false; });
                }
            };
        }

        function carListing() {
            return {
                cars: [], loading: false, loadingMore: false,
                page: 1, hasMore: false, totalCount: 0,
                showMobileFilters: false,
                filters: {
                    search: '{{ request("search", "") }}',
                    make: '{{ request("make", "") }}',
                    condition: '{{ request("condition", "") }}',
                    transmission: '{{ request("transmission", "") }}',
                    fuel_type: '{{ request("fuel_type", "") }}',
                    category: '{{ isset($category) ? $category->slug : request("category", "") }}',
                    city: '{{ request("city", "") }}',
                    latitude: '{{ request("latitude", "") }}',
                    longitude: '{{ request("longitude", "") }}',
                    min_price: '{{ request("min_price", "") }}',
                    max_price: '{{ request("max_price", "") }}',
                    min_year: '{{ request("min_year", "") }}',
                    max_year: '{{ request("max_year", "") }}',
                },
                labelMaps: {
                    category: { @foreach($categories ?? [] as $cat) '{{ $cat->slug }}': '{{ $cat->name }}', @endforeach },
                    condition: { @foreach($conditions ?? [] as $o) '{{ $o->value }}': '{{ $o->label }}', @endforeach },
                    transmission: { @foreach($transmissions ?? [] as $o) '{{ $o->value }}': '{{ $o->label }}', @endforeach },
                    fuel_type: { @foreach($fuelTypes ?? [] as $o) '{{ $o->value }}': '{{ $o->label }}', @endforeach },
                },
                get activeFilterCount() {
                    return Object.entries(this.filters).filter(([k, v]) => v && !['latitude','longitude'].includes(k)).length;
                },

                init() { this.fetchCars(); },

                hasActiveFilters() {
                    return Object.entries(this.filters).some(([k, v]) => v && !['latitude','longitude'].includes(k));
                },

                async applyFilters() {
                    this.page = 1;
                    this.cars = [];
                    this.updateUrl();
                    await this.fetchCars();
                },

                clearAllFilters() {
                    Object.keys(this.filters).forEach(k => this.filters[k] = '');
                    this.applyFilters();
                },

                updateUrl() {
                    const p = new URLSearchParams();
                    Object.entries(this.filters).forEach(([k, v]) => { if (v) p.set(k, v); });
                    const qs = p.toString();
                    const url = '{{ isset($category) ? route("cars.category", $category) : route("cars.index") }}' + (qs ? '?' + qs : '');
                    history.pushState(null, '', url);
                },

                async fetchCars() {
                    this.loading = true;
                    try {
                        const p = new URLSearchParams();
                        Object.entries(this.filters).forEach(([k, v]) => { if (v) p.set(k, v); });
                        p.set('page', this.page);
                        p.set('per_page', 12);
                        const r = await fetch('/api/cars/listing?' + p.toString());
                        const data = await r.json();
                        if (this.page === 1) {
                            this.cars = data.data;
                        } else {
                            this.cars = [...this.cars, ...data.data];
                        }
                        this.hasMore = data.meta.has_more;
                        this.totalCount = data.meta.total;
                    } catch(e) { console.error('Fetch error:', e); }
                    this.loading = false;
                },

                async loadMore() {
                    this.loadingMore = true;
                    this.page++;
                    try {
                        const p = new URLSearchParams();
                        Object.entries(this.filters).forEach(([k, v]) => { if (v) p.set(k, v); });
                        p.set('page', this.page);
                        p.set('per_page', 12);
                        const r = await fetch('/api/cars/listing?' + p.toString());
                        const data = await r.json();
                        this.cars = [...this.cars, ...data.data];
                        this.hasMore = data.meta.has_more;
                        this.totalCount = data.meta.total;
                    } catch(e) { console.error('Load more error:', e); }
                    this.loadingMore = false;
                }
            };
        }
    </script>
    @endpush
</x-layouts.public>
