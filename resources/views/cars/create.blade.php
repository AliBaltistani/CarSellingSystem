<x-layouts.public>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Sell Your Car</h1>
            <p class="text-slate-600 mt-1">Fill in the details below to list your car</p>
        </div>

        <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Basic Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            placeholder="e.g., 2023 BMW X5 M Sport - Low Mileage"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Make *</label>
                        <input type="text" name="make" value="{{ old('make') }}" required
                            placeholder="e.g., BMW, Mercedes, Toyota"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('make') border-red-500 @enderror">
                        @error('make')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Model *</label>
                        <input type="text" name="model" value="{{ old('model') }}" required
                            placeholder="e.g., X5, C-Class, Camry"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('model') border-red-500 @enderror">
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Year *</label>
                        <input type="number" name="year" value="{{ old('year') }}" required min="1900" max="{{ date('Y') + 1 }}"
                            placeholder="e.g., 2023"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('year') border-red-500 @enderror">
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Category *</label>
                        <select name="category_id" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Price (AED) *</label>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1"
                            placeholder="e.g., 150000"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Condition *</label>
                        <select name="condition" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="">Select</option>
                            @foreach($dropdownOptions['conditions'] ?? [] as $option)
                                <option value="{{ $option->value }}" {{ old('condition') == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                            @endforeach
                        </select>
                        @error('condition')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Specifications -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Specifications</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Mileage (km)</label>
                        <input type="number" name="mileage" value="{{ old('mileage') }}" min="0"
                            placeholder="e.g., 50000"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Transmission *</label>
                        <select name="transmission" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            <option value="">Select</option>
                            @foreach($dropdownOptions['transmissions'] ?? [] as $option)
                                <option value="{{ $option->value }}" {{ old('transmission') == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Fuel Type *</label>
                        <select name="fuel_type" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            <option value="">Select</option>
                            @foreach($dropdownOptions['fuelTypes'] ?? [] as $option)
                                <option value="{{ $option->value }}" {{ old('fuel_type') == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Body Type</label>
                        <select name="body_type"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            <option value="">Select</option>
                            @foreach($dropdownOptions['bodyTypes'] ?? [] as $option)
                                <option value="{{ $option->value }}" {{ old('body_type') == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Exterior Color</label>
                        <select name="exterior_color"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            <option value="">Select</option>
                            @foreach($dropdownOptions['exteriorColors'] ?? [] as $option)
                                <option value="{{ $option->value }}" {{ old('exterior_color') == $option->value ? 'selected' : '' }}
                                    @if($option->color) style="background: {{ $option->color }}15;" @endif>
                                    {{ $option->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Description</h2>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Description * (min 50 characters)</label>
                    <textarea name="description" rows="6" required minlength="50"
                        placeholder="Describe your car in detail. Include history, features, condition, reason for selling, etc."
                        class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Contact Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">WhatsApp Number *</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" required
                            placeholder="e.g., +971501234567"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 @error('whatsapp_number') border-red-500 @enderror">
                        @error('whatsapp_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                            placeholder="e.g., +971501234567"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">City</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            placeholder="e.g., Dubai, Abu Dhabi"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Country</label>
                        <input type="text" name="country" value="{{ old('country', 'UAE') }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Photos</h2>
                <p class="text-slate-600 text-sm mb-4">Upload up to 10 high-quality images (max 5MB each)</p>
                
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-amber-400 transition-colors">
                    <input type="file" name="images[]" multiple accept="image/*" id="images" class="hidden">
                    <label for="images" class="cursor-pointer">
                        <svg class="w-12 h-12 mx-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-2 text-slate-600">Click to upload photos</p>
                        <p class="text-sm text-slate-400">JPEG, PNG, WebP up to 5MB</p>
                    </label>
                </div>
                @error('images.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('cars.my-listings') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-lg transition-all shadow-lg shadow-orange-500/25">
                    Publish Listing
                </button>
            </div>
        </form>
    </div>
</x-layouts.public>
