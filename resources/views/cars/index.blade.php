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
                <form action="{{ isset($category) ? route('cars.category', $category) : route('cars.index') }}" method="GET" class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Filters</h2>

                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Make, model, keyword..."
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>

                    <!-- Make - Searchable -->
                    <div class="mb-6" x-data="{ open: false, search: '', selected: '{{ request('make') }}' }" @click.away="open = false">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Make</label>
                        <input type="hidden" name="make" :value="selected">
                        <div class="relative">
                            <button type="button" @click="open = !open" 
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-left flex items-center justify-between bg-white">
                                <span x-text="selected || 'All Makes'" :class="selected ? 'text-slate-900' : 'text-slate-500'"></span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="absolute top-full left-0 right-0 bg-white shadow-lg border border-slate-200 rounded-lg mt-1 z-50 max-h-64 overflow-hidden">
                                <div class="p-2 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search makes..." 
                                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <button type="button" @click="selected = ''; open = false" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">All Makes</button>
                                    @foreach($makes ?? [] as $make)
                                    <button type="button" x-show="!search || '{{ strtolower($make->label) }}'.includes(search.toLowerCase())"
                                        @click="selected = '{{ $make->value }}'; open = false" 
                                        class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50"
                                        :class="selected === '{{ $make->value }}' ? 'bg-amber-50 text-amber-700' : 'text-slate-700'">
                                        {{ $make->label }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
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

                    <!-- Condition - Searchable -->
                    <div class="mb-6" x-data="{ open: false, search: '', selected: '{{ request('condition') }}' }" @click.away="open = false">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Condition</label>
                        <input type="hidden" name="condition" :value="selected">
                        <div class="relative">
                            <button type="button" @click="open = !open" 
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-left flex items-center justify-between bg-white">
                                <span x-text="selected ? conditionLabels[selected] : 'All Conditions'" :class="selected ? 'text-slate-900' : 'text-slate-500'"></span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="absolute top-full left-0 right-0 bg-white shadow-lg border border-slate-200 rounded-lg mt-1 z-50 max-h-64 overflow-hidden">
                                <div class="p-2 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search conditions..." 
                                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <button type="button" @click="selected = ''; open = false" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">All Conditions</button>
                                    @foreach($conditions ?? [] as $option)
                                    <button type="button" x-show="!search || '{{ strtolower($option->label) }}'.includes(search.toLowerCase())"
                                        @click="selected = '{{ $option->value }}'; open = false" 
                                        class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50"
                                        :class="selected === '{{ $option->value }}' ? 'bg-amber-50 text-amber-700' : 'text-slate-700'">
                                        {{ $option->label }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transmission - Searchable -->
                    <div class="mb-6" x-data="{ open: false, search: '', selected: '{{ request('transmission') }}' }" @click.away="open = false">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Transmission</label>
                        <input type="hidden" name="transmission" :value="selected">
                        <div class="relative">
                            <button type="button" @click="open = !open" 
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-left flex items-center justify-between bg-white">
                                <span x-text="selected ? transmissionLabels[selected] : 'All Types'" :class="selected ? 'text-slate-900' : 'text-slate-500'"></span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="absolute top-full left-0 right-0 bg-white shadow-lg border border-slate-200 rounded-lg mt-1 z-50 max-h-64 overflow-hidden">
                                <div class="p-2 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search transmissions..." 
                                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <button type="button" @click="selected = ''; open = false" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">All Types</button>
                                    @foreach($transmissions ?? [] as $option)
                                    <button type="button" x-show="!search || '{{ strtolower($option->label) }}'.includes(search.toLowerCase())"
                                        @click="selected = '{{ $option->value }}'; open = false" 
                                        class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50"
                                        :class="selected === '{{ $option->value }}' ? 'bg-amber-50 text-amber-700' : 'text-slate-700'">
                                        {{ $option->label }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fuel Type - Searchable -->
                    <div class="mb-6" x-data="{ open: false, search: '', selected: '{{ request('fuel_type') }}' }" @click.away="open = false">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Fuel Type</label>
                        <input type="hidden" name="fuel_type" :value="selected">
                        <div class="relative">
                            <button type="button" @click="open = !open" 
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-left flex items-center justify-between bg-white">
                                <span x-text="selected ? fuelTypeLabels[selected] : 'All Fuel Types'" :class="selected ? 'text-slate-900' : 'text-slate-500'"></span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="absolute top-full left-0 right-0 bg-white shadow-lg border border-slate-200 rounded-lg mt-1 z-50 max-h-64 overflow-hidden">
                                <div class="p-2 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search fuel types..." 
                                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                                <div class="max-h-48 overflow-y-auto">
                                    <button type="button" @click="selected = ''; open = false" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 text-slate-500">All Fuel Types</button>
                                    @foreach($fuelTypes ?? [] as $option)
                                    <button type="button" x-show="!search || '{{ strtolower($option->label) }}'.includes(search.toLowerCase())"
                                        @click="selected = '{{ $option->value }}'; open = false" 
                                        class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50"
                                        :class="selected === '{{ $option->value }}' ? 'bg-amber-50 text-amber-700' : 'text-slate-700'">
                                        {{ $option->label }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Attribute Filters -->
                    @if(isset($filterableAttributes) && $filterableAttributes->count() > 0)
                        <div class="border-t border-slate-200 pt-4 mt-4">
                            <h3 class="text-md font-semibold text-slate-800 mb-4">Advanced Filters</h3>
                            
                            @foreach($filterableAttributes as $groupName => $attributes)
                                @if($attributes->count() > 0)
                                    <div class="mb-4">
                                        <h4 class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">{{ $groupName }}</h4>
                                        
                                        @foreach($attributes as $attribute)
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                                    @if($attribute->icon)<span class="mr-1">{{ $attribute->icon }}</span>@endif
                                                    {{ $attribute->name }}
                                                </label>
                                                
                                                @switch($attribute->type)
                                                    @case('select')
                                                        <select name="attr[{{ $attribute->id }}]" 
                                                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                                                            <option value="">Any {{ $attribute->name }}</option>
                                                            @foreach($attribute->options as $option)
                                                                <option value="{{ $option->value }}" 
                                                                    {{ request("attr.{$attribute->id}") == $option->value ? 'selected' : '' }}>
                                                                    {{ $option->label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @break

                                                    @case('multiselect')
                                                        <div class="space-y-1 max-h-28 overflow-y-auto bg-slate-50 rounded-lg p-2">
                                                            @foreach($attribute->options as $option)
                                                                <label class="flex items-center gap-2 cursor-pointer text-sm">
                                                                    <input type="checkbox" name="attr[{{ $attribute->id }}][]" 
                                                                        value="{{ $option->value }}"
                                                                        {{ in_array($option->value, (array)request("attr.{$attribute->id}", [])) ? 'checked' : '' }}
                                                                        class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                                                                    <span class="text-slate-700">{{ $option->label }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        @break

                                                    @case('boolean')
                                                        <select name="attr[{{ $attribute->id }}]" 
                                                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                                                            <option value="">Any</option>
                                                            <option value="1" {{ request("attr.{$attribute->id}") === '1' ? 'selected' : '' }}>Yes</option>
                                                            <option value="0" {{ request("attr.{$attribute->id}") === '0' ? 'selected' : '' }}>No</option>
                                                        </select>
                                                        @break

                                                    @case('color')
                                                        <div class="flex flex-wrap gap-2">
                                                            @foreach($attribute->options as $option)
                                                                <label class="cursor-pointer">
                                                                    <input type="radio" name="attr[{{ $attribute->id }}]" 
                                                                        value="{{ $option->value }}" class="sr-only peer"
                                                                        {{ request("attr.{$attribute->id}") == $option->value ? 'checked' : '' }}>
                                                                    <span class="block w-7 h-7 rounded-full border-2 border-slate-200 peer-checked:border-amber-500 peer-checked:ring-2 peer-checked:ring-amber-200"
                                                                        style="background-color: {{ $option->color ?? $option->value }}" 
                                                                        title="{{ $option->label }}"></span>
                                                                </label>
                                                            @endforeach
                                                            <label class="cursor-pointer flex items-center">
                                                                <input type="radio" name="attr[{{ $attribute->id }}]" 
                                                                    value="" class="sr-only peer"
                                                                    {{ !request("attr.{$attribute->id}") ? 'checked' : '' }}>
                                                                <span class="text-xs text-slate-500 peer-checked:text-amber-600">Any</span>
                                                            </label>
                                                        </div>
                                                        @break

                                                    @default
                                                        <input type="text" name="attr[{{ $attribute->id }}]" 
                                                            value="{{ request("attr.{$attribute->id}") }}"
                                                            placeholder="{{ $attribute->placeholder ?: "Enter {$attribute->name}" }}"
                                                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500">
                                                @endswitch
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-lg transition-all">
                            Apply Filters
                        </button>
                        <a href="{{ isset($category) ? route('cars.category', $category) : route('cars.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
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
                        <a href="{{ isset($category) ? route('cars.category', $category) : route('cars.index') }}" class="inline-block mt-6 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors">
                            Clear All Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Label mappings for searchable dropdowns
        const conditionLabels = {
            @foreach($conditions ?? [] as $option)
            '{{ $option->value }}': '{{ $option->label }}',
            @endforeach
        };
        
        const transmissionLabels = {
            @foreach($transmissions ?? [] as $option)
            '{{ $option->value }}': '{{ $option->label }}',
            @endforeach
        };
        
        const fuelTypeLabels = {
            @foreach($fuelTypes ?? [] as $option)
            '{{ $option->value }}': '{{ $option->label }}',
            @endforeach
        };
    </script>
    @endpush
</x-layouts.public>
