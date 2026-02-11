<x-layouts.admin :title="$offer->exists ? 'Edit Offer' : 'Add Offer'">
    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.offers.index') }}" class="inline-flex items-center text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Offers
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-xl font-bold text-slate-900 mb-6">
                {{ $offer->exists ? 'Edit Offer' : 'Add New Offer' }}
            </h1>

            <form action="{{ $offer->exists ? route('admin.offers.update', $offer) : route('admin.offers.store') }}" 
                method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if($offer->exists)
                    @method('PUT')
                @endif

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Offer Title *</label>
                    <input type="text" id="title" name="title" 
                        value="{{ old('title', $offer->title) }}" required placeholder="e.g. Toyota Excellence Package"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Icon Upload -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Icon</label>
                        @if($offer->icon)
                            <div class="mb-3">
                                <img src="{{ $offer->icon_url }}" alt="Current icon" class="h-12 w-12 object-contain bg-slate-100 p-2 rounded">
                            </div>
                        @endif
                        <input type="file" name="icon" accept="image/*"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                        <p class="mt-1 text-xs text-slate-500">Small icon, max 1MB</p>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Image</label>
                        @if($offer->image)
                            <div class="mb-3">
                                <img src="{{ $offer->image_url }}" alt="Current image" class="h-12 max-w-24 object-cover bg-slate-100 rounded">
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                        <p class="mt-1 text-xs text-slate-500">Larger image, max 2MB</p>
                    </div>
                </div>

                <!-- Badge -->
                <div>
                    <label for="badge" class="block text-sm font-medium text-slate-700 mb-1">Badge Text</label>
                    <input type="text" id="badge" name="badge" 
                        value="{{ old('badge', $offer->badge) }}" placeholder="e.g. 100% DOWN PAYMENT, FREE INSURANCE"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Price Label -->
                    <div>
                        <label for="price_label" class="block text-sm font-medium text-slate-700 mb-1">Price Label</label>
                        <input type="text" id="price_label" name="price_label" 
                            value="{{ old('price_label', $offer->price_label) }}" placeholder="e.g. PREMIUM"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>

                    <!-- Price From -->
                    <div>
                        <label for="price_from" class="block text-sm font-medium text-slate-700 mb-1">Starting From (AED)</label>
                        <input type="number" id="price_from" name="price_from" step="0.01" min="0"
                            value="{{ old('price_from', $offer->price_from) }}" placeholder="0.00"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>

                    <!-- Price Upgrade -->
                    <div>
                        <label for="price_upgrade" class="block text-sm font-medium text-slate-700 mb-1">Upgrade From (AED)</label>
                        <input type="number" id="price_upgrade" name="price_upgrade" step="0.01" min="0"
                            value="{{ old('price_upgrade', $offer->price_upgrade) }}" placeholder="0.00"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                    <x-forms.rich-editor name="description" :value="$offer->description" height="150px" />
                </div>

                <!-- Features -->
                <div>
                    <label for="features" class="block text-sm font-medium text-slate-700 mb-1">Features (one per line)</label>
                    <textarea id="features" name="features" rows="4" placeholder="Free service for 1 year&#10;Extended warranty&#10;Road assistance"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('features', is_array($offer->features) ? implode("\n", $offer->features) : '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CTA Text -->
                    <div>
                        <label for="cta_text" class="block text-sm font-medium text-slate-700 mb-1">Button Text</label>
                        <input type="text" id="cta_text" name="cta_text" 
                            value="{{ old('cta_text', $offer->cta_text ?? 'Read More') }}" placeholder="Read More"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>

                    <!-- CTA Link -->
                    <div>
                        <label for="cta_link" class="block text-sm font-medium text-slate-700 mb-1">Button Link</label>
                        <input type="text" id="cta_link" name="cta_link" 
                            value="{{ old('cta_link', $offer->cta_link) }}" placeholder="/contact or external URL"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Stripe Integration -->
                <div>
                    <label for="stripe_price_id" class="block text-sm font-medium text-slate-700 mb-1">Stripe Price ID</label>
                    <input type="text" id="stripe_price_id" name="stripe_price_id" 
                        value="{{ old('stripe_price_id', $offer->stripe_price_id) }}" placeholder="price_1ABC..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-slate-500">Optional. Pre-created Stripe price ID for this offer.</p>
                    @error('stripe_price_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-slate-700 mb-1">Display Order</label>
                        <input type="number" id="order" name="order" 
                            value="{{ old('order', $offer->order ?? 0) }}" min="0"
                            class="w-32 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>

                    <!-- Expires At -->
                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-slate-700 mb-1">Expires At</label>
                        <input type="date" id="expires_at" name="expires_at" 
                            value="{{ old('expires_at', $offer->expires_at?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <p class="mt-1 text-xs text-slate-500">Leave empty for no expiry</p>
                    </div>
                </div>

                <!-- Status Toggles -->
                <div class="flex items-center space-x-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                            {{ old('is_active', $offer->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500">
                        <span class="ml-2 text-sm text-slate-700">Active</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" 
                            {{ old('is_featured', $offer->is_featured) ? 'checked' : '' }}
                            class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500">
                        <span class="ml-2 text-sm text-slate-700">Featured</span>
                    </label>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('admin.offers.index') }}" 
                        class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium rounded-lg transition-all shadow-sm">
                        {{ $offer->exists ? 'Update Offer' : 'Create Offer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
