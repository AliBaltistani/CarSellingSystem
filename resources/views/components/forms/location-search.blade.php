@props([
    'name',
    'value' => '',
    'label' => null,
    'placeholder' => 'Search location...',
    'required' => false,
    'latitude' => null,
    'longitude' => null,
    'endpoint' => '/api/locations/combined',
    'autoSave' => true
])

<div x-data="locationSearchComponent({
    name: '{{ $name }}',
    value: '{{ $value }}',
    latitude: {{ $latitude ?? 'null' }},
    longitude: {{ $longitude ?? 'null' }},
    endpoint: '{{ $endpoint }}',
    autoSave: {{ $autoSave ? 'true' : 'false' }}
})" class="relative" {{ $attributes }}>
    
    @if($label)
    <label class="block text-sm font-medium text-slate-700 mb-2">{{ $label }} @if($required) * @endif</label>
    @endif

    <div class="relative">
        <input type="text" 
            x-model="searchQuery"
            @input.debounce.400ms="searchLocations()"
            @focus="showResults = true"
            @keydown.enter.prevent
            placeholder="{{ $placeholder }}"
            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 @error($name) border-red-500 @enderror"
            {{ $required ? 'required' : '' }}
        >
        
        <!-- Loading Indicator -->
        <div x-show="searching" class="absolute right-3 top-3.5">
            <svg class="animate-spin h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Clear Button -->
        <button type="button" x-show="searchQuery.length > 0 && !searching" 
                @click="clearLocation()"
                class="absolute right-3 top-3.5 text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Hidden Inputs (Only if name is provided) -->
    @if($name)
    <input type="hidden" name="{{ $name }}" x-model="selectedCity">
    <input type="hidden" name="address" x-model="selectedDisplayName">
    <input type="hidden" name="state" x-model="selectedState">
    <input type="hidden" name="country" x-model="selectedCountry">
    <input type="hidden" name="county" x-model="selectedCounty">
    <input type="hidden" name="latitude" x-model="selectedLat">
    <input type="hidden" name="longitude" x-model="selectedLon">
    <input type="hidden" name="display_name" x-model="selectedDisplayName">
    @endif

    <!-- Results Dropdown -->
    <div x-show="showResults && (results.length > 0 || searching)" 
         x-transition 
         @click.away="showResults = false"
         class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-72 overflow-y-auto">
        
        <template x-for="(result, index) in results" :key="result.source === 'db' ? 'db-'+result.id : (result.place_id ? 'api-'+result.place_id : 'idx-'+index)">
            <button type="button" @click="selectLocation(result)"
                class="w-full px-4 py-3 text-left hover:bg-amber-50 border-b border-slate-100 last:border-0 transition-colors">
                <div class="flex items-start gap-3">
                    <!-- Location icon with source badge -->
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="relative">
                            <svg class="w-5 h-5" :class="result.source === 'db' ? 'text-amber-500' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <!-- Primary name (city or first part of display_name) -->
                        <div class="font-medium text-slate-900 truncate" x-text="result.city || result.name || result.display_name.split(',')[0]"></div>
                        <!-- Structured location info -->
                        <div class="text-xs text-slate-500 mt-0.5 truncate">
                            <span x-show="result.state" x-text="result.state"></span>
                            <span x-show="result.state && result.country">, </span>
                            <span x-show="result.country" x-text="result.country"></span>
                            <span x-show="result.county" class="text-slate-400" x-text="' · ' + result.county"></span>
                        </div>
                        <!-- Full address for street-level results -->
                        <div x-show="result.road || result.neighbourhood" class="text-xs text-slate-400 mt-0.5 truncate" 
                             x-text="[result.road, result.neighbourhood].filter(Boolean).join(', ')"></div>
                    </div>
                    <!-- Source badge -->
                    <span x-show="result.source === 'db'" 
                          class="flex-shrink-0 text-[10px] font-medium text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded-full mt-0.5">Saved</span>
                </div>
            </button>
        </template>
        
        <div x-show="!searching && results.length === 0 && searchQuery.length >= 2" class="px-4 py-3 text-slate-500 text-center text-sm">
            No locations found
        </div>
    </div>
    
    @if($name)
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @endif
</div>

@once
<script>
    function locationSearchComponent(config) {
        return {
            searchQuery: config.value || '',
            results: [],
            showResults: false,
            searching: false,
            selectedCity: config.value || '',
            selectedState: '',
            selectedCountry: '',
            selectedCounty: '',
            selectedLat: config.latitude || null,
            selectedLon: config.longitude || null,
            selectedDisplayName: '',

            init() {
                // Listen for external location set (e.g. from geolocation auto-detect)
                window.addEventListener('set-location', (e) => {
                    const d = e.detail;
                    if (d) {
                        this.selectedCity = d.city || '';
                        this.selectedState = d.state || '';
                        this.selectedCountry = d.country || '';
                        this.selectedLat = d.lat || null;
                        this.selectedLon = d.lon || null;
                        this.selectedDisplayName = d.display_name || '';
                        this.searchQuery = d.display_name || d.city || '';
                    }
                });
            },

            async searchLocations() {
                if (this.searchQuery.length < 2) {
                    this.results = [];
                    return;
                }

                this.searching = true;
                try {
                    const response = await fetch(`${config.endpoint}?q=${encodeURIComponent(this.searchQuery)}`);
                    if (response.ok) {
                        this.results = await response.json();
                    }
                } catch (error) {
                    console.error('Location search failed:', error);
                } finally {
                    this.searching = false;
                }
            },

            selectLocation(result) {
                this.selectedCity = result.city || result.name;
                this.selectedState = result.state || '';
                this.selectedCountry = result.country || '';
                this.selectedCounty = result.county || '';
                this.selectedLat = result.lat;
                this.selectedLon = result.lon;
                this.selectedDisplayName = result.display_name;
                
                // Update input display
                this.searchQuery = result.display_name;
                
                this.showResults = false;
                this.results = [];

                // Dispatch event with full location data
                this.$dispatch('location-selected', {
                    city: this.selectedCity,
                    state: this.selectedState,
                    country: this.selectedCountry,
                    county: this.selectedCounty,
                    lat: this.selectedLat,
                    lon: this.selectedLon,
                    display_name: this.selectedDisplayName,
                    source: result.source,
                    road: result.road || null,
                    neighbourhood: result.neighbourhood || null,
                    postcode: result.postcode || null,
                });

                // Auto-save new locations from API if enabled
                if (config.autoSave && result.source === 'api') {
                     this.saveLocationToDb(result);
                }
            },

            clearLocation() {
                this.searchQuery = '';
                this.selectedCity = '';
                this.selectedState = '';
                this.selectedCountry = '';
                this.selectedCounty = '';
                this.selectedLat = null;
                this.selectedLon = null;
                this.selectedDisplayName = '';
                this.results = [];
                this.showResults = false;
                
                this.$dispatch('location-cleared');
            },

            async saveLocationToDb(result) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!csrfToken) return;

                    await fetch('/api/locations/create', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            city: result.city,
                            state: result.state,
                            country: result.country,
                            display_name: result.name || result.city,
                            address: result.display_name || null,
                            county: result.county || null,
                            neighbourhood: result.neighbourhood || null,
                            postcode: result.postcode || null,
                            lat: result.lat,
                            lon: result.lon
                        })
                    });
                } catch (error) {
                    console.warn('Failed to save location to DB:', error);
                }
            }
        }
    }
</script>
@endonce
