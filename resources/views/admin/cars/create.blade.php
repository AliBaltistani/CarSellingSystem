<x-layouts.admin title="Add New Car">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Add New Car</h1>
        <p class="text-slate-600">Create a new car listing</p>
    </div>

    <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Basic Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 @error('title') border-red-500 @enderror">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Make *</label>
                    <input type="text" name="make" value="{{ old('make') }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Model *</label>
                    <input type="text" name="model" value="{{ old('model') }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Year *</label>
                    <input type="number" name="year" value="{{ old('year', date('Y')) }}" required min="1900" max="{{ date('Y') + 1 }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="">Select Category</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Price (AED) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" required min="0"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Condition *</label>
                    <select name="condition" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Used</option>
                        <option value="certified" {{ old('condition') == 'certified' ? 'selected' : '' }}>Certified Pre-Owned</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Specifications -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Specifications</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mileage (km)</label>
                    <input type="number" name="mileage" value="{{ old('mileage') }}" min="0"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Transmission *</label>
                    <select name="transmission" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="automatic" {{ old('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="cvt" {{ old('transmission') == 'cvt' ? 'selected' : '' }}>CVT</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Fuel Type *</label>
                    <select name="fuel_type" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>Electric</option>
                        <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Body Type</label>
                    <input type="text" name="body_type" value="{{ old('body_type') }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Exterior Color</label>
                    <input type="text" name="exterior_color" value="{{ old('exterior_color') }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Interior Color</label>
                    <input type="text" name="interior_color" value="{{ old('interior_color') }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Doors</label>
                    <input type="number" name="doors" value="{{ old('doors') }}" min="2" max="6"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Seats</label>
                    <input type="number" name="seats" value="{{ old('seats') }}" min="2" max="12"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Description</h2>
            <x-forms.rich-editor name="description" value="" height="200px" :required="true" />
        </div>

        <!-- Dynamic Attributes Section -->
        <div id="dynamic-attributes-section" class="hidden">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900">Custom Specifications</h2>
                    <span id="attributes-loading" class="hidden text-sm text-slate-500">
                        <svg class="animate-spin h-4 w-4 inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </div>
                <div id="dynamic-attributes-container">
                    <!-- Attributes will be loaded here dynamically -->
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const categorySelect = document.querySelector('select[name="category_id"]');
                const section = document.getElementById('dynamic-attributes-section');
                const container = document.getElementById('dynamic-attributes-container');
                const loading = document.getElementById('attributes-loading');

                categorySelect.addEventListener('change', async function() {
                    const categoryId = this.value;
                    
                    if (!categoryId) {
                        section.classList.add('hidden');
                        container.innerHTML = '';
                        return;
                    }

                    // Show loading
                    section.classList.remove('hidden');
                    loading.classList.remove('hidden');
                    container.innerHTML = '';

                    try {
                        const response = await fetch(`/api/categories/${categoryId}/attributes`);
                        const groups = await response.json();

                        if (groups.length === 0) {
                            container.innerHTML = '<p class="text-slate-500 text-sm">No custom attributes for this category.</p>';
                        } else {
                            container.innerHTML = renderAttributeGroups(groups);
                        }
                    } catch (error) {
                        console.error('Error loading attributes:', error);
                        container.innerHTML = '<p class="text-red-500 text-sm">Error loading attributes.</p>';
                    }

                    loading.classList.add('hidden');
                });

                // Trigger if category already selected (edit mode or old value)
                if (categorySelect.value) {
                    categorySelect.dispatchEvent(new Event('change'));
                }
            });

            function renderAttributeGroups(groups) {
                return groups.map(group => `
                    <div class="mb-6 last:mb-0">
                        <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <span>${group.group_icon}</span>
                            ${group.group}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            ${group.attributes.map(attr => renderAttributeField(attr)).join('')}
                        </div>
                    </div>
                `).join('<hr class="my-6 border-slate-200">');
            }

            function renderAttributeField(attr) {
                const required = attr.is_required ? 'required' : '';
                const requiredMark = attr.is_required ? ' *' : '';
                const fieldName = `attributes[${attr.id}]`;
                const oldValue = ''; // Will be populated in edit mode

                let input = '';

                switch (attr.type) {
                    case 'text':
                        input = `<input type="text" name="${fieldName}" ${required}
                            placeholder="${attr.placeholder || ''}"
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">`;
                        break;

                    case 'textarea':
                        input = `<textarea name="${fieldName}" rows="3" ${required}
                            placeholder="${attr.placeholder || ''}"
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm"></textarea>`;
                        break;

                    case 'number':
                        input = `<div class="relative">
                            ${attr.prefix ? `<span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">${attr.prefix}</span>` : ''}
                            <input type="number" name="${fieldName}" ${required}
                                placeholder="${attr.placeholder || ''}"
                                step="${attr.step || 'any'}"
                                ${attr.min_value !== null ? `min="${attr.min_value}"` : ''}
                                ${attr.max_value !== null ? `max="${attr.max_value}"` : ''}
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm ${attr.prefix ? 'pl-12' : ''} ${attr.suffix ? 'pr-12' : ''}">
                            ${attr.suffix ? `<span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">${attr.suffix}</span>` : ''}
                        </div>`;
                        break;

                    case 'select':
                        input = `<select name="${fieldName}" ${required}
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">
                            <option value="">Select...</option>
                            ${attr.options.map(opt => `<option value="${opt.value}" ${opt.is_default ? 'selected' : ''}>${opt.label}</option>`).join('')}
                        </select>`;
                        break;

                    case 'multiselect':
                        input = `<div class="space-y-2 max-h-32 overflow-y-auto p-2 bg-slate-50 rounded-lg">
                            ${attr.options.map(opt => `
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="${fieldName}[]" value="${opt.value}" ${opt.is_default ? 'checked' : ''}
                                        class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                                    <span class="text-sm text-slate-700">${opt.label}</span>
                                </label>
                            `).join('')}
                        </div>`;
                        break;

                    case 'boolean':
                        input = `<div class="flex items-center gap-3 pt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="${fieldName}" value="1"
                                    class="w-4 h-4 border-slate-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-sm text-slate-700">Yes</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="${fieldName}" value="0" checked
                                    class="w-4 h-4 border-slate-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-sm text-slate-700">No</span>
                            </label>
                        </div>`;
                        break;

                    case 'date':
                        input = `<input type="date" name="${fieldName}" ${required}
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">`;
                        break;

                    case 'color':
                        input = `<div class="flex flex-wrap gap-2 pt-2">
                            ${attr.options.map(opt => `
                                <label class="cursor-pointer group">
                                    <input type="radio" name="${fieldName}" value="${opt.value}" class="sr-only peer" ${opt.is_default ? 'checked' : ''}>
                                    <span class="block w-8 h-8 rounded-full border-2 border-slate-200 peer-checked:border-amber-500 peer-checked:ring-2 peer-checked:ring-amber-200"
                                        style="background-color: ${opt.color || opt.value}" title="${opt.label}"></span>
                                </label>
                            `).join('')}
                        </div>`;
                        break;

                    case 'range':
                        input = `<div class="space-y-2">
                            <input type="range" name="${fieldName}"
                                min="${attr.min_value || 0}" max="${attr.max_value || 100}" step="${attr.step || 1}"
                                class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-amber-500"
                                oninput="this.nextElementSibling.textContent = this.value">
                            <div class="text-center text-sm text-slate-600">${attr.default_value || Math.round((attr.min_value || 0 + attr.max_value || 100) / 2)}</div>
                        </div>`;
                        break;

                    default:
                        input = `<input type="text" name="${fieldName}" ${required}
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">`;
                }

                return `
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            ${attr.icon ? `<span class="mr-1">${attr.icon}</span>` : ''}
                            ${attr.name}${requiredMark}
                        </label>
                        ${input}
                        ${attr.help_text ? `<p class="mt-1 text-xs text-slate-500">${attr.help_text}</p>` : ''}
                    </div>
                `;
            }
        </script>

        <!-- Contact & Location -->
        <div class="bg-white rounded-xl shadow-sm p-6" x-data="locationSearch()">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Contact & Location</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">WhatsApp Number *</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                
                <!-- Location Search -->
                <div class="lg:col-span-2 relative">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Location (City) *</label>
                    <input type="text" x-model="searchQuery" 
                        @input.debounce.400ms="searchLocations()"
                        @focus="showResults = true"
                        placeholder="Search city..."
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    
                    <!-- Hidden fields for form submission -->
                    <input type="hidden" name="city" x-bind:value="selectedCity">
                    <input type="hidden" name="country" x-bind:value="selectedCountry">
                    
                    <!-- Search Results Dropdown -->
                    <div x-show="showResults && (results.length > 0 || searching)" x-transition @click.away="showResults = false"
                        class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl max-h-64 overflow-y-auto">
                        <template x-if="searching">
                            <div class="px-4 py-3 text-slate-500 text-center">Searching...</div>
                        </template>
                        <template x-for="result in results" :key="result.display_name">
                            <button type="button" @click="selectLocation(result)"
                                class="w-full px-4 py-3 text-left hover:bg-amber-50 border-b border-slate-100 last:border-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium text-slate-900" x-text="result.city"></span>
                                        <span class="text-sm text-slate-500" x-text="', ' + result.country"></span>
                                    </div>
                                    <span x-show="result.source === 'db'" class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">Saved</span>
                                    <span x-show="result.source === 'api'" class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">New</span>
                                </div>
                            </button>
                        </template>
                        <template x-if="!searching && results.length === 0 && searchQuery.length >= 2">
                            <div class="px-4 py-3 text-slate-500 text-center">No locations found</div>
                        </template>
                    </div>
                    
                    <!-- Selected Location Display -->
                    <div x-show="selectedCity" class="mt-2 text-sm text-green-600">
                        Selected: <span x-text="selectedCity + ', ' + selectedCountry"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function locationSearch() {
                return {
                    searchQuery: '{{ old('city') }}',
                    results: [],
                    searching: false,
                    showResults: false,
                    selectedCity: '{{ old('city') }}',
                    selectedCountry: '{{ old('country', 'Pakistan') }}',

                    async searchLocations() {
                        if (this.searchQuery.length < 2) {
                            this.results = [];
                            return;
                        }

                        this.searching = true;
                        this.showResults = true;

                        try {
                            const response = await fetch(`/api/locations/combined?q=${encodeURIComponent(this.searchQuery)}`);
                            this.results = await response.json();
                        } catch (error) {
                            console.error('Location search error:', error);
                            this.results = [];
                        }

                        this.searching = false;
                    },

                    async selectLocation(result) {
                        this.selectedCity = result.city;
                        this.selectedCountry = result.country;
                        this.searchQuery = result.city;
                        this.showResults = false;
                        this.results = [];

                        // If it's from API, save it to DB
                        if (result.source === 'api') {
                            try {
                                await fetch('/api/locations/create', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        city: result.city,
                                        state: result.state,
                                        country: result.country,
                                        display_name: result.display_name,
                                        lat: result.lat,
                                        lon: result.lon
                                    })
                                });
                            } catch (error) {
                                console.error('Failed to save location:', error);
                            }
                        }
                    }
                }
            }
        </script>

        <!-- Status & Options -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Status & Options</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-sm font-medium text-slate-700">Published</span>
                </label>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-sm font-medium text-slate-700">Featured</span>
                </label>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="negotiable" value="1" {{ old('negotiable') ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-sm font-medium text-slate-700">Price Negotiable</span>
                </label>
            </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Images</h2>
            <input type="file" name="images[]" multiple accept="image/*"
                class="w-full px-4 py-2 border border-slate-200 rounded-lg">
            <p class="mt-2 text-sm text-slate-500">Upload multiple images (max 10, 5MB each)</p>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.cars.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg">Create Car</button>
        </div>
    </form>
</x-layouts.admin>
