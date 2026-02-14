<x-layouts.admin title="Edit Car">
    <style>[x-cloak] { display: none !important; }</style>
    <div x-data="adminCarEditStepper()" x-cloak>

        <!-- Step Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-5 left-0 right-0 h-0.5 bg-slate-200 mx-10"></div>
                <div class="absolute top-5 left-0 h-0.5 bg-teal-800 mx-10 transition-all duration-500"
                    :style="'width: ' + ((currentStep - 1) / (totalSteps - 1) * 100) + '%'"></div>

                <template x-for="step in steps" :key="step.number">
                    <div class="flex flex-col items-center relative z-10 cursor-pointer" @click="goToStep(step.number)">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300"
                            :class="{
                                'bg-teal-800 border-teal-800 text-white shadow-lg shadow-teal-800/30': currentStep === step.number,
                                'bg-teal-800 border-teal-800 text-white': currentStep > step.number,
                                'bg-white border-slate-300 text-slate-400': currentStep < step.number
                            }">
                            <template x-if="currentStep > step.number">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </template>
                            <template x-if="currentStep <= step.number">
                                <span x-text="step.number"></span>
                            </template>
                        </div>
                        <span class="mt-2 text-xs font-medium hidden sm:block transition-colors"
                            :class="currentStep >= step.number ? 'text-teal-800' : 'text-slate-400'"
                            x-text="step.shortTitle"></span>
                    </div>
                </template>
            </div>

            <div class="text-center mt-4">
                <span class="text-sm font-semibold text-teal-800" x-text="steps[currentStep - 1].title"></span>
            </div>
        </div>

        <!-- Step Header Card -->
        <div class="bg-teal-800 rounded-xl p-5 mb-6">
            <h1 class="text-xl font-bold text-white" x-text="steps[currentStep - 1].heading"></h1>
            <p class="text-teal-200 text-sm mt-1" x-text="steps[currentStep - 1].description"></p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <p class="font-semibold text-sm mb-1">Please fix the following errors:</p>
                <ul class="text-sm list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.cars.update', $car) }}" method="POST" enctype="multipart/form-data" id="admin-car-edit-form" novalidate>
            @csrf
            @method('PUT')

            <!-- Step 1: Basic Information -->
            <div x-show="currentStep === 1" data-step="1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="bg-white rounded-xl shadow-sm p-6" x-data="carMakeModel()">
                    @php
                        // Logic to find the ID of the current make (string)
                        $currentMakeLabel = old('make', $car->make);
                        $currentMakeId = null;
                        if (!empty($dropdownOptions['makes'])) {
                            foreach ($dropdownOptions['makes'] as $option) {
                                if ($option->label === $currentMakeLabel) {
                                    $currentMakeId = $option->id;
                                    break;
                                }
                            }
                        }
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Title *</label>
                            <input type="text" name="title" value="{{ old('title', $car->title) }}" required
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 @error('title') border-red-500 @enderror">
                            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Make *</label>
                            <select required x-model="selectedMakeId" @change="fetchModels()"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                <option value="">Select Make</option>
                                @foreach($dropdownOptions['makes'] ?? [] as $option)
                                    <option value="{{ $option->id }}" data-label="{{ $option->label }}" {{ (old('make') == $option->label || $currentMakeId == $option->id) ? 'selected' : '' }}>{{ $option->label }}</option>
                                @endforeach
                            </select>
                            {{-- Store the Label (Name) in the 'make' field --}}
                            <input type="hidden" name="make" :value="selectedMakeLabel">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Model *</label>
                            <select name="model" required x-model="selectedModel" :disabled="!selectedMakeId"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 disabled:bg-slate-100 disabled:text-slate-400">
                                <option value="">Select Model</option>
                                <template x-for="model in models" :key="model.id">
                                    <option :value="model.label" x-text="model.label"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Year *</label>
                            <select name="year" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                <option value="">Select Year</option>
                                @for($i = date('Y') + 1; $i >= 1900; $i--)
                                    <option value="{{ $i }}" {{ old('year', $car->year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Category *</label>
                            <select name="category_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                <option value="">Select Category</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $car->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Price (AED) *</label>
                            <input type="number" name="price" value="{{ old('price', $car->price) }}" required min="0"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Condition *</label>
                            <select name="condition" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                <option value="">Select Condition</option>
                                @foreach($dropdownOptions['conditions'] as $option)
                                    <option value="{{ $option->value }}" {{ old('condition', $car->condition) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Specifications -->
            <div x-show="currentStep === 2" data-step="2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Mileage (km)</label>
                                <input type="number" name="mileage" value="{{ old('mileage', $car->mileage) }}"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            </div>


                            {{-- Condition moved to Step 1 --}}

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Transmission *</label>
                                <select name="transmission" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                    <option value="">Select Transmission</option>
                                    @foreach($dropdownOptions['transmissions'] as $option)
                                        <option value="{{ $option->value }}" {{ old('transmission', $car->transmission) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Fuel Type *</label>
                                <select name="fuel_type" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                    <option value="">Select Fuel Type</option>
                                    @foreach($dropdownOptions['fuel_types'] as $option)
                                        <option value="{{ $option->value }}" {{ old('fuel_type', $car->fuel_type) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Body Type</label>
                                <select name="body_type"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                    <option value="">Select Body Type</option>
                                    @foreach($dropdownOptions['body_types'] as $option)
                                        <option value="{{ $option->value }}" {{ old('body_type', $car->body_type) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Exterior Color</label>
                                <select name="exterior_color"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                    <option value="">Select Color</option>
                                    @foreach($dropdownOptions['exterior_colors'] as $option)
                                        <option value="{{ $option->value }}" {{ old('exterior_color', $car->exterior_color) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Interior Color</label>
                                <select name="interior_color"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                    <option value="">Select Color</option>
                                    @foreach($dropdownOptions['interior_colors'] as $option)
                                        <option value="{{ $option->value }}" {{ old('interior_color', $car->interior_color) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Doors</label>
                                <select name="doors"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                    <option value="">Select Doors</option>
                                    @foreach($dropdownOptions['doors'] as $option)
                                        <option value="{{ $option->value }}" {{ old('doors', $car->doors) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Seats</label>
                                <select name="seats"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                    <option value="">Select Seats</option>
                                    @foreach($dropdownOptions['seats'] as $option)
                                        <option value="{{ $option->value }}" {{ old('seats', $car->seats) == $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>

                <!-- Dynamic Attributes Section -->
                <div id="dynamic-attributes-section" class="hidden mt-6">
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
                        <div id="dynamic-attributes-container"></div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Description -->
            <div x-show="currentStep === 3" data-step="3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <x-forms.rich-editor name="description" :value="$car->description" height="200px" :required="true" />
                </div>
            </div>

            <!-- Step 4: Contact & Location -->
            <div x-show="currentStep === 4" data-step="4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="bg-white rounded-xl shadow-sm p-6">
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

                        <!-- Location Search -->
                        <div class="lg:col-span-2">
                            <x-forms.location-search 
                                name="city" 
                                label="Location (City)"
                                placeholder="Search city..."
                                required="true"
                                :value="old('city', $car->city)"
                                :latitude="$car->latitude"
                                :longitude="$car->longitude"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5: Status & Options -->
            <div x-show="currentStep === 5" data-step="5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="col-span-1 md:col-span-2 lg:col-span-4 mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Status *</label>
                            <select name="status" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="sold" {{ old('status', $car->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="pending" {{ old('status', $car->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reserved" {{ old('status', $car->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                            </select>
                        </div>
                        <label class="flex items-center space-x-3 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 cursor-pointer transition-colors">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $car->is_published) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                            <div>
                                <span class="text-sm font-medium text-slate-700 block">Published</span>
                                <span class="text-xs text-slate-500">Visible to public</span>
                            </div>
                        </label>
                        <label class="flex items-center space-x-3 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 cursor-pointer transition-colors">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $car->is_featured) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                            <div>
                                <span class="text-sm font-medium text-slate-700 block">Featured</span>
                                <span class="text-xs text-slate-500">Show in featured section</span>
                            </div>
                        </label>

                        <label class="flex items-center space-x-3 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 cursor-pointer transition-colors">
                            <input type="checkbox" name="negotiable" value="1" {{ old('negotiable', $car->negotiable) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500">
                            <div>
                                <span class="text-sm font-medium text-slate-700 block">Negotiable</span>
                                <span class="text-xs text-slate-500">Buyers can negotiate</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Step 6: Photos -->
            <div x-show="currentStep === 6" data-step="6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <!-- Current Images -->
                @if($car->images->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
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

                <!-- Add More Images -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Add More Photos</h2>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-amber-400 transition-colors">
                        <input type="file" name="images[]" multiple accept="image/*" id="admin-edit-images" class="hidden" @change="previewImages($event)">
                        <label for="admin-edit-images" class="cursor-pointer">
                            <svg class="w-12 h-12 mx-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-slate-600">Click to upload photos</p>
                            <p class="text-sm text-slate-400">Upload multiple images (max 10, 2MB each)</p>
                        </label>
                    </div>

                    <div x-show="imagePreviews.length > 0" class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <template x-for="(src, index) in imagePreviews" :key="index">
                            <div class="relative group">
                                <img :src="src" class="w-full h-24 object-cover rounded-lg border border-slate-200">
                                <span class="absolute top-1 right-1 px-2 py-0.5 bg-emerald-500 text-white text-xs rounded">New</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center mt-8">
                <div>
                    <button type="button" x-show="currentStep > 1"
                        @click="prevStep()"
                        class="inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.cars.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition-colors">
                        Cancel
                    </a>

                    <button type="button" x-show="currentStep < totalSteps"
                        @click="nextStep()"
                        class="inline-flex items-center px-8 py-2.5 bg-gradient-to-r from-teal-700 to-teal-800 hover:from-teal-800 hover:to-teal-900 text-white font-semibold rounded-lg transition-all shadow-lg shadow-teal-800/25">
                        Next Step
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <button type="button" x-show="currentStep === totalSteps" @click="submitForm()"
                        class="inline-flex items-center px-8 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-lg transition-all shadow-lg shadow-orange-500/25">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Car
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Existing attribute values from the database
        const existingValues = @json($car->attributeValues->pluck('value', 'attribute_id'));

        function carMakeModel() {
            return {
                selectedMakeId: '',
                selectedMakeLabel: '',
                selectedModel: '',
                models: [],
                
                init() {
                    // Initialize with existing values
                    this.selectedMakeId = '{{ $currentMakeId }}';
                    this.selectedMakeLabel = '{{ old('make', $car->make) }}';
                    this.selectedModel = '{{ old('model', $car->model) }}';
                    
                    if (this.selectedMakeId) {
                        this.fetchModels();
                    }
                },

                async fetchModels() {
                    this.models = [];
                    // Update label based on selected ID
                    const select = document.querySelector('select[x-model="selectedMakeId"]');
                    if (select && select.selectedOptions[0]) {
                        this.selectedMakeLabel = select.selectedOptions[0].getAttribute('data-label') || '';
                    }

                    if (!this.selectedMakeId) {
                         // Only clear if we really have no selection (and not just initial load where DOM might be readying)
                         // But for edit, we have ID.
                         // If user clears it?
                         if (!this.selectedMakeId && this.selectedMakeLabel) {
                             // This mismatch shouldn't happen if they select "Select Make"
                             this.selectedMakeLabel = '';
                         }
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
                }
            }
        }



        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.querySelector('select[name="category_id"]');
            const section = document.getElementById('dynamic-attributes-section');
            const container = document.getElementById('dynamic-attributes-container');
            const loading = document.getElementById('attributes-loading');

            async function loadAttributes(categoryId, skipLoading = false) {
                if (!categoryId) { section.classList.add('hidden'); container.innerHTML = ''; return; }
                section.classList.remove('hidden');
                if (!skipLoading) { loading.classList.remove('hidden'); container.innerHTML = ''; }
                try {
                    const response = await fetch(`/api/categories/${categoryId}/attributes`);
                    const groups = await response.json();
                    if (groups.length === 0) { container.innerHTML = '<p class="text-slate-500 text-sm">No custom attributes for this category.</p>'; }
                    else { container.innerHTML = renderAttributeGroups(groups); }
                } catch (error) { console.error('Error loading attributes:', error); container.innerHTML = '<p class="text-red-500 text-sm">Error loading attributes.</p>'; }
                loading.classList.add('hidden');
            }

            categorySelect.addEventListener('change', function() { loadAttributes(this.value); });
            if (categorySelect.value) { loadAttributes(categorySelect.value, true); }
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
                    input = `<input type="text" name="${fieldName}" value="${existingValue}" ${required} placeholder="${attr.placeholder || ''}" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">`;
                    break;
                case 'textarea':
                    input = `<textarea name="${fieldName}" rows="3" ${required} placeholder="${attr.placeholder || ''}" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">${existingValue}</textarea>`;
                    break;
                case 'number':
                    input = `<div class="relative">
                        ${attr.prefix ? `<span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">${attr.prefix}</span>` : ''}
                        <input type="number" name="${fieldName}" value="${existingValue}" ${required} placeholder="${attr.placeholder || ''}" step="${attr.step || 'any'}" ${attr.min_value !== null ? `min="${attr.min_value}"` : ''} ${attr.max_value !== null ? `max="${attr.max_value}"` : ''} class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm ${attr.prefix ? 'pl-12' : ''} ${attr.suffix ? 'pr-12' : ''}">
                        ${attr.suffix ? `<span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">${attr.suffix}</span>` : ''}
                    </div>`;
                    break;
                case 'select':
                    input = `<select name="${fieldName}" ${required} class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">
                        <option value="">Select...</option>
                        ${attr.options.map(opt => `<option value="${opt.value}" ${existingValue === opt.value || (!existingValue && opt.is_default) ? 'selected' : ''}>${opt.label}</option>`).join('')}
                    </select>`;
                    break;
                case 'multiselect':
                    const selectedValues = existingValue ? (typeof existingValue === 'string' ? JSON.parse(existingValue || '[]') : existingValue) : [];
                    input = `<div class="space-y-2 max-h-32 overflow-y-auto p-2 bg-slate-50 rounded-lg">
                        ${attr.options.map(opt => `<label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="${fieldName}[]" value="${opt.value}" ${selectedValues.includes(opt.value) || (!existingValue && opt.is_default) ? 'checked' : ''} class="w-4 h-4 rounded border-slate-300 text-amber-500 focus:ring-amber-500"><span class="text-sm text-slate-700">${opt.label}</span></label>`).join('')}
                    </div>`;
                    break;
                case 'boolean':
                    const isTrue = existingValue === '1' || existingValue === 1 || existingValue === true;
                    input = `<div class="flex items-center gap-3 pt-2">
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="${fieldName}" value="1" ${isTrue ? 'checked' : ''} class="w-4 h-4 border-slate-300 text-amber-500 focus:ring-amber-500"><span class="text-sm text-slate-700">Yes</span></label>
                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="${fieldName}" value="0" ${!isTrue ? 'checked' : ''} class="w-4 h-4 border-slate-300 text-amber-500 focus:ring-amber-500"><span class="text-sm text-slate-700">No</span></label>
                    </div>`;
                    break;
                case 'date':
                    input = `<input type="date" name="${fieldName}" value="${existingValue}" ${required} class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">`;
                    break;
                case 'color':
                    input = `<div class="flex flex-wrap gap-2 pt-2">
                        ${attr.options.map(opt => `<label class="cursor-pointer group"><input type="radio" name="${fieldName}" value="${opt.value}" class="sr-only peer" ${existingValue === opt.value || (!existingValue && opt.is_default) ? 'checked' : ''}><span class="block w-8 h-8 rounded-full border-2 border-slate-200 peer-checked:border-amber-500 peer-checked:ring-2 peer-checked:ring-amber-200" style="background-color: ${opt.color || opt.value}" title="${opt.label}"></span></label>`).join('')}
                    </div>`;
                    break;
                case 'range':
                    const rangeValue = existingValue || attr.default_value || Math.round((attr.min_value || 0 + attr.max_value || 100) / 2);
                    input = `<div class="space-y-2">
                        <input type="range" name="${fieldName}" value="${rangeValue}" min="${attr.min_value || 0}" max="${attr.max_value || 100}" step="${attr.step || 1}" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-amber-500" oninput="this.nextElementSibling.textContent = this.value">
                        <div class="text-center text-sm text-slate-600">${rangeValue}</div>
                    </div>`;
                    break;
                default:
                    input = `<input type="text" name="${fieldName}" value="${existingValue}" ${required} class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">`;
            }
            return `<div><label class="block text-sm font-medium text-slate-700 mb-1">${attr.icon ? `<span class="mr-1">${attr.icon}</span>` : ''}${attr.name}${requiredMark}</label>${input}${attr.help_text ? `<p class="mt-1 text-xs text-slate-500">${attr.help_text}</p>` : ''}</div>`;
        }

        function adminCarEditStepper() {
            return {
                currentStep: 1,
                totalSteps: 6,
                imagePreviews: [],
                validationErrors: [],
                steps: [
                    { number: 1, shortTitle: 'Basic Info', title: 'Basic Information', heading: 'Update Car Details', description: 'Edit the basic details like make, model, year, condition and price' },
                    { number: 2, shortTitle: 'Specs', title: 'Specifications', heading: 'Technical Specifications', description: 'Update technical details and specifications of the vehicle' },
                    { number: 3, shortTitle: 'Description', title: 'Description', heading: 'Car Description', description: 'Revise the vehicle description' },
                    { number: 4, shortTitle: 'Contact', title: 'Contact & Location', heading: 'Contact & Location', description: 'Update contact details and listing location' },
                    { number: 5, shortTitle: 'Status', title: 'Status & Options', heading: 'Listing Options', description: 'Update publication status and listing options' },
                    { number: 6, shortTitle: 'Photos', title: 'Manage Photos', heading: 'Car Photos', description: 'Review existing photos and upload new ones' },
                ],

                validateStep(step) {
                    this.validationErrors = [];
                    // Scope to the edit form to avoid conflicts with other elements (like the create form if loaded in same DOM, though unlikely here)
                    // But more importantly, ensure we're looking at the right step within *this* form
                    const form = document.getElementById('admin-car-edit-form');
                    if (!form) return true;
                    
                    const stepEl = form.querySelector(`[data-step="${step}"]`);
                    if (!stepEl) return true;
                    
                    const requiredFields = stepEl.querySelectorAll('[required]');
                    let isValid = true;
                    requiredFields.forEach(field => {
                        field.classList.remove('border-red-500', 'ring-red-500');
                        let fieldValid = true;
                        
                        // Check if field is visible (handle cases where x-show might hide parents)
                        if (field.offsetParent === null) return;

                        if (field.type === 'checkbox' || field.type === 'radio') {
                            const name = field.getAttribute('name');
                            const checked = stepEl.querySelectorAll(`[name="${name}"]:checked`);
                            fieldValid = checked.length > 0;
                        } else if (field.tagName === 'SELECT') {
                            fieldValid = field.value !== '';
                        } else {
                            fieldValid = field.value.trim() !== '';
                            if (fieldValid && field.minLength > 0 && field.value.trim().length < field.minLength) {
                                fieldValid = false;
                                this.validationErrors.push(`${field.previousElementSibling?.textContent?.trim() || 'Field'} must be at least ${field.minLength} characters`);
                            }
                        }
                        if (!fieldValid) {
                            isValid = false;
                            field.classList.add('border-red-500', 'ring-red-500');
                            field.style.animation = 'shake 0.5s ease-in-out';
                            setTimeout(() => field.style.animation = '', 500);
                            const label = field.closest('div')?.querySelector('label');
                            const fieldName = label?.textContent?.trim()?.replace(' *', '') || 'This field';
                            if (!this.validationErrors.find(e => e.includes(fieldName))) {
                                this.validationErrors.push(`${fieldName} is required`);
                            }
                        }
                    });
                    if (!isValid) this.showValidationToast();
                    return isValid;
                },

                showValidationToast() {
                    const existing = document.getElementById('validation-toast');
                    if (existing) existing.remove();
                    const toast = document.createElement('div');
                    toast.id = 'validation-toast';
                    toast.className = 'fixed top-4 right-4 z-50 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl shadow-2xl max-w-sm';
                    toast.style.animation = 'slideIn 0.3s ease-out';
                    toast.innerHTML = `<div class="flex items-start gap-3"><svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><div><p class="font-semibold text-sm">Please fill in required fields</p><ul class="text-xs mt-1 space-y-0.5">${this.validationErrors.map(e => `<li>• ${e}</li>`).join('')}</ul></div></div>`;
                    document.body.appendChild(toast);
                    setTimeout(() => { toast.style.animation = 'fadeOut 0.3s ease-in'; setTimeout(() => toast.remove(), 300); }, 4000);
                },

                nextStep() {
                    if (this.currentStep < this.totalSteps && this.validateStep(this.currentStep)) {
                        this.currentStep++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                goToStep(step) {
                    if (step > this.currentStep) {
                        for (let i = this.currentStep; i < step; i++) {
                            if (!this.validateStep(i)) return;
                        }
                    }
                    this.currentStep = step;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                submitForm() {
                    if (this.validateStep(this.currentStep)) {
                        document.getElementById('admin-car-edit-form').submit();
                    }
                },

                previewImages(event) {
                    this.imagePreviews = [];
                    const files = event.target.files;
                    const maxSize = 2 * 1024 * 1024; // 2MB

                    for (let i = 0; i < files.length; i++) {
                        if (files[i].size > maxSize) {
                            alert(`File "${files[i].name}" is too large. Maximum size is 2MB per image.`);
                            event.target.value = ''; // Clear input
                            this.imagePreviews = [];
                            return;
                        }
                    }

                    for (let i = 0; i < files.length; i++) {
                        const reader = new FileReader();
                        reader.onload = (e) => { this.imagePreviews.push(e.target.result); };
                        reader.readAsDataURL(files[i]);
                    }
                }
            }
        }

        // Validation animations
        const validationStyles = document.createElement('style');
        validationStyles.textContent = `
            @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
            @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
            @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
        `;
        document.head.appendChild(validationStyles);
    </script>
</x-layouts.admin>
