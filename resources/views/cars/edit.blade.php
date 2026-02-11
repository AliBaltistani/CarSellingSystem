<x-layouts.public>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Edit Listing</h1>
            <p class="text-slate-600 mt-1">Update your car listing details</p>
        </div>

        <form action="{{ route('cars.update', $car) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Title *</label>
                        <input type="text" name="title" value="{{ old('title', $car->title) }}" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Make *</label>
                        <input type="text" name="make" value="{{ old('make', $car->make) }}" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Model *</label>
                        <input type="text" name="model" value="{{ old('model', $car->model) }}" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Year *</label>
                        <input type="number" name="year" value="{{ old('year', $car->year) }}" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Category *</label>
                        <select name="category_id" required class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $car->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Price (AED) *</label>
                        <input type="number" name="price" value="{{ old('price', $car->price) }}" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Condition *</label>
                        <select name="condition" required class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            @foreach($dropdownOptions['conditions'] ?? [] as $option)
                                <option value="{{ $option->value }}" {{ old('condition', $car->condition) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Specifications -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Specifications</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Mileage (km)</label>
                        <input type="number" name="mileage" value="{{ old('mileage', $car->mileage) }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Transmission *</label>
                        <select name="transmission" required class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            @foreach($dropdownOptions['transmissions'] ?? [] as $option)
                                <option value="{{ $option->value }}" {{ old('transmission', $car->transmission) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Fuel Type *</label>
                        <select name="fuel_type" required class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            @foreach($dropdownOptions['fuelTypes'] ?? [] as $option)
                                <option value="{{ $option->value }}" {{ old('fuel_type', $car->fuel_type) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Body Type</label>
                        <select name="body_type" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            <option value="">Select</option>
                            @foreach($dropdownOptions['bodyTypes'] ?? [] as $option)
                                <option value="{{ $option->value }}" {{ old('body_type', $car->body_type) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Exterior Color</label>
                        <select name="exterior_color" class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            <option value="">Select</option>
                            @foreach($dropdownOptions['exteriorColors'] ?? [] as $option)
                                <option value="{{ $option->value }}" {{ old('exterior_color', $car->exterior_color) == $option->value ? 'selected' : '' }}
                                    @if($option->color) style="background: {{ $option->color }}15;" @endif>
                                    {{ $option->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
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
                // Existing attribute values from the database
                const existingValues = @json($car->attributeValues->pluck('value', 'attribute_id'));

                document.addEventListener('DOMContentLoaded', function() {
                    const categorySelect = document.querySelector('select[name="category_id"]');
                    const section = document.getElementById('dynamic-attributes-section');
                    const container = document.getElementById('dynamic-attributes-container');
                    const loading = document.getElementById('attributes-loading');

                    async function loadAttributes(categoryId, skipLoading = false) {
                        if (!categoryId) {
                            section.classList.add('hidden');
                            container.innerHTML = '';
                            return;
                        }

                        section.classList.remove('hidden');
                        if (!skipLoading) {
                            loading.classList.remove('hidden');
                            container.innerHTML = '';
                        }

                        try {
                            const response = await fetch(`/api/categories/${categoryId}/attributes`);
                            const groups = await response.json();

                            if (groups.length === 0) {
                                section.classList.add('hidden');
                            } else {
                                container.innerHTML = renderAttributeGroups(groups);
                            }
                        } catch (error) {
                            console.error('Error loading attributes:', error);
                            container.innerHTML = '<p class="text-red-500 text-sm">Error loading attributes.</p>';
                        }

                        loading.classList.add('hidden');
                    }

                    categorySelect.addEventListener('change', function() {
                        loadAttributes(this.value);
                    });

                    // Load attributes on page load for existing car
                    if (categorySelect.value) {
                        loadAttributes(categorySelect.value, true);
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
                    const existingValue = existingValues[attr.id] || attr.default_value || '';

                    let input = '';

                    switch (attr.type) {
                        case 'text':
                            input = `<input type="text" name="${fieldName}" value="${existingValue}" ${required}
                                placeholder="${attr.placeholder || ''}"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">`;
                            break;
                        case 'textarea':
                            input = `<textarea name="${fieldName}" rows="3" ${required}
                                placeholder="${attr.placeholder || ''}"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">${existingValue}</textarea>`;
                            break;
                        case 'number':
                            input = `<div class="relative">
                                ${attr.prefix ? `<span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">${attr.prefix}</span>` : ''}
                                <input type="number" name="${fieldName}" value="${existingValue}" ${required}
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
                                ${attr.options.map(opt => `<option value="${opt.value}" ${existingValue === opt.value || (!existingValue && opt.is_default) ? 'selected' : ''}>${opt.label}</option>`).join('')}
                            </select>`;
                            break;
                        case 'multiselect':
                            const selectedValues = existingValue ? (typeof existingValue === 'string' ? JSON.parse(existingValue || '[]') : existingValue) : [];
                            input = `<div class="space-y-2 max-h-32 overflow-y-auto p-2 bg-slate-50 rounded-lg">
                                ${attr.options.map(opt => `
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="${fieldName}[]" value="${opt.value}" ${selectedValues.includes(opt.value) || (!existingValue && opt.is_default) ? 'checked' : ''}
                                            class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                                        <span class="text-sm text-slate-700">${opt.label}</span>
                                    </label>
                                `).join('')}
                            </div>`;
                            break;
                        case 'boolean':
                            const isTrue = existingValue === '1' || existingValue === 1 || existingValue === true;
                            input = `<div class="flex items-center gap-3 pt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="${fieldName}" value="1" ${isTrue ? 'checked' : ''}
                                        class="w-4 h-4 border-slate-300 text-amber-500 focus:ring-amber-500">
                                    <span class="text-sm text-slate-700">Yes</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="${fieldName}" value="0" ${!isTrue ? 'checked' : ''}
                                        class="w-4 h-4 border-slate-300 text-amber-500 focus:ring-amber-500">
                                    <span class="text-sm text-slate-700">No</span>
                                </label>
                            </div>`;
                            break;
                        case 'date':
                            input = `<input type="date" name="${fieldName}" value="${existingValue}" ${required}
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">`;
                            break;
                        case 'color':
                            input = `<div class="flex flex-wrap gap-2 pt-2">
                                ${attr.options.map(opt => `
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="${fieldName}" value="${opt.value}" class="sr-only peer" ${existingValue === opt.value || (!existingValue && opt.is_default) ? 'checked' : ''}>
                                        <span class="block w-8 h-8 rounded-full border-2 border-slate-200 peer-checked:border-amber-500 peer-checked:ring-2 peer-checked:ring-amber-200"
                                            style="background-color: ${opt.color || opt.value}" title="${opt.label}"></span>
                                    </label>
                                `).join('')}
                            </div>`;
                            break;
                        case 'range':
                            const rangeValue = existingValue || attr.default_value || Math.round(((attr.min_value || 0) + (attr.max_value || 100)) / 2);
                            input = `<div class="space-y-2">
                                <input type="range" name="${fieldName}" value="${rangeValue}"
                                    min="${attr.min_value || 0}" max="${attr.max_value || 100}" step="${attr.step || 1}"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-amber-500"
                                    oninput="this.nextElementSibling.textContent = this.value">
                                <div class="text-center text-sm text-slate-600">${rangeValue}</div>
                            </div>`;
                            break;
                        default:
                            input = `<input type="text" name="${fieldName}" value="${existingValue}" ${required}
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

            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Description</h2>
                <textarea name="description" rows="6" required minlength="50"
                    class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('description', $car->description) }}</textarea>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Contact Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">WhatsApp Number *</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $car->whatsapp_number) }}" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $car->phone_number) }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">City</label>
                        <input type="text" name="city" value="{{ old('city', $car->city) }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Country</label>
                        <input type="text" name="country" value="{{ old('country', $car->country) }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <!-- Current Images -->
            @if($car->images->count() > 0)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Current Photos</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($car->images as $image)
                        <div class="relative group">
                            <img src="{{ $image->url }}" alt="" class="w-full h-24 object-cover rounded-lg">
                            @if($image->is_primary)
                                <span class="absolute top-1 left-1 px-2 py-0.5 bg-amber-500 text-white text-xs rounded">Primary</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Add More Images -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Add More Photos</h2>
                <input type="file" name="images[]" multiple accept="image/*" 
                    class="w-full px-4 py-3 border border-slate-200 rounded-lg">
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('cars.my-listings') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-lg transition-all shadow-lg shadow-orange-500/25">
                    Update Listing
                </button>
            </div>
        </form>
    </div>
</x-layouts.public>
