<x-layouts.admin title="Edit Car">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit Car</h1>
        <p class="text-slate-600">Update car listing details</p>
    </div>

    <form action="{{ route('admin.cars.update', $car) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Basic Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $car->title) }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Make *</label>
                    <input type="text" name="make" value="{{ old('make', $car->make) }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Model *</label>
                    <input type="text" name="model" value="{{ old('model', $car->model) }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Year *</label>
                    <input type="number" name="year" value="{{ old('year', $car->year) }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $car->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Price (AED) *</label>
                    <input type="number" name="price" value="{{ old('price', $car->price) }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Condition *</label>
                    <select name="condition" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="new" {{ old('condition', $car->condition) == 'new' ? 'selected' : '' }}>New</option>
                        <option value="used" {{ old('condition', $car->condition) == 'used' ? 'selected' : '' }}>Used</option>
                        <option value="certified" {{ old('condition', $car->condition) == 'certified' ? 'selected' : '' }}>Certified Pre-Owned</option>
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
                    <input type="number" name="mileage" value="{{ old('mileage', $car->mileage) }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Transmission *</label>
                    <select name="transmission" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="automatic" {{ old('transmission', $car->transmission) == 'automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="manual" {{ old('transmission', $car->transmission) == 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="cvt" {{ old('transmission', $car->transmission) == 'cvt' ? 'selected' : '' }}>CVT</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Fuel Type *</label>
                    <select name="fuel_type" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="petrol" {{ old('fuel_type', $car->fuel_type) == 'petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="diesel" {{ old('fuel_type', $car->fuel_type) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="electric" {{ old('fuel_type', $car->fuel_type) == 'electric' ? 'selected' : '' }}>Electric</option>
                        <option value="hybrid" {{ old('fuel_type', $car->fuel_type) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Body Type</label>
                    <input type="text" name="body_type" value="{{ old('body_type', $car->body_type) }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Exterior Color</label>
                    <input type="text" name="exterior_color" value="{{ old('exterior_color', $car->exterior_color) }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Interior Color</label>
                    <input type="text" name="interior_color" value="{{ old('interior_color', $car->interior_color) }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Doors</label>
                    <input type="number" name="doors" value="{{ old('doors', $car->doors) }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Seats</label>
                    <input type="number" name="seats" value="{{ old('seats', $car->seats) }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Description</h2>
            <textarea name="description" rows="5" required
                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">{{ old('description', $car->description) }}</textarea>
        </div>

        <!-- Contact & Location -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Contact & Location</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">WhatsApp Number *</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $car->whatsapp_number) }}" required
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $car->phone_number) }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">City</label>
                    <input type="text" name="city" value="{{ old('city', $car->city) }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Country</label>
                    <input type="text" name="country" value="{{ old('country', $car->country) }}"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        <!-- Status & Options -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Status & Options</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $car->is_published) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-sm font-medium text-slate-700">Published</span>
                </label>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $car->is_featured) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-sm font-medium text-slate-700">Featured</span>
                </label>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="is_sold" value="1" {{ old('is_sold', $car->is_sold) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-sm font-medium text-slate-700">Sold</span>
                </label>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="negotiable" value="1" {{ old('negotiable', $car->negotiable) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-sm font-medium text-slate-700">Negotiable</span>
                </label>
            </div>
        </div>

        <!-- Current Images -->
        @if($car->images->count() > 0)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Current Images</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($car->images as $image)
                    <div class="relative group">
                        <img src="{{ $image->url }}" alt="" class="w-full h-24 object-cover rounded-lg">
                        @if($image->is_primary)
                            <span class="absolute top-1 left-1 px-2 py-0.5 bg-amber-500 text-white text-xs rounded">Primary</span>
                        @endif
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-2">
                            <form action="{{ route('admin.cars.set-primary-image', $image) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-1 bg-white rounded text-amber-600 hover:bg-amber-100" title="Set Primary">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                            </form>
                            <form action="{{ route('admin.cars.delete-image', $image) }}" method="POST" onsubmit="return confirm('Delete image?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 bg-white rounded text-red-600 hover:bg-red-100" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Add Images -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Add More Images</h2>
            <input type="file" name="images[]" multiple accept="image/*"
                class="w-full px-4 py-2 border border-slate-200 rounded-lg">
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.cars.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg">Update Car</button>
        </div>
    </form>
</x-layouts.admin>
