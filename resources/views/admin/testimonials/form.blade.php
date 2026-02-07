<x-layouts.admin :title="$testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial'">
    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Testimonials
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-xl font-bold text-slate-900 mb-6">
                {{ $testimonial->exists ? 'Edit Testimonial' : 'Add New Testimonial' }}
            </h1>

            <form action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" 
                method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if($testimonial->exists)
                    @method('PUT')
                @endif

                <!-- Customer Name -->
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-slate-700 mb-1">Customer Name *</label>
                    <input type="text" id="customer_name" name="customer_name" 
                        value="{{ old('customer_name', $testimonial->customer_name) }}" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('customer_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Designation -->
                <div>
                    <label for="designation" class="block text-sm font-medium text-slate-700 mb-1">Designation / Title</label>
                    <input type="text" id="designation" name="designation" 
                        value="{{ old('designation', $testimonial->designation) }}" placeholder="e.g. Business Owner, Car Buyer"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rating *</label>
                    <div class="flex items-center space-x-1" x-data="{ rating: {{ old('rating', $testimonial->rating ?? 5) }} }">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" @click="rating = {{ $i }}" 
                                class="p-1 focus:outline-none transition-colors">
                                <svg class="w-8 h-8 transition-colors" :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-slate-300'" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                        <input type="hidden" name="rating" :value="rating">
                    </div>
                </div>

                <!-- Review Text -->
                <div>
                    <label for="review" class="block text-sm font-medium text-slate-700 mb-1">Review Text *</label>
                    <textarea id="review" name="review" rows="4" required placeholder="Customer's testimonial text..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('review', $testimonial->review) }}</textarea>
                    @error('review')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo Upload -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Customer Photo</label>
                    @if($testimonial->customer_photo)
                        <div class="mb-3">
                            <img src="{{ $testimonial->photo_url }}" alt="Current photo" class="w-20 h-20 rounded-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="customer_photo" accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    <p class="mt-1 text-sm text-slate-500">Leave empty to keep current photo or use auto-generated avatar</p>
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-slate-700 mb-1">Display Order</label>
                    <input type="number" id="order" name="order" 
                        value="{{ old('order', $testimonial->order ?? 0) }}" min="0"
                        class="w-32 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-slate-500">Lower numbers appear first</p>
                </div>

                <!-- Status Toggles -->
                <div class="flex items-center space-x-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                            {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500">
                        <span class="ml-2 text-sm text-slate-700">Active (visible on website)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" 
                            {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }}
                            class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500">
                        <span class="ml-2 text-sm text-slate-700">Featured</span>
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('admin.testimonials.index') }}" 
                        class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium rounded-lg transition-all shadow-sm">
                        {{ $testimonial->exists ? 'Update Testimonial' : 'Create Testimonial' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
