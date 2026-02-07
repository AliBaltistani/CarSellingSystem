<x-layouts.public :seo="$seo ?? []">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-slate-500 hover:text-amber-600">Home</a></li>
                <li class="text-slate-400">/</li>
                <li><a href="{{ route('cars.index') }}" class="text-slate-500 hover:text-amber-600">Cars</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-900 font-medium truncate">{{ $car->title }}</li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Image Gallery -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden" x-data="{ activeImage: 0 }">
                    <div class="aspect-[4/3] bg-slate-100 relative">
                        @if($car->images->count() > 0)
                            @foreach($car->images as $index => $image)
                                <img x-show="activeImage === {{ $index }}" 
                                    src="{{ $image->url }}" alt="{{ $car->title }}"
                                    class="w-full h-full object-cover" x-transition>
                            @endforeach
                        @else
                            <img src="{{ $car->main_image }}" alt="{{ $car->title }}" class="w-full h-full object-cover">
                        @endif

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex space-x-2">
                            @if($car->is_featured)
                                <span class="px-3 py-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-full">
                                    Featured
                                </span>
                            @endif
                            <span class="px-3 py-1 bg-slate-900/70 backdrop-blur-sm text-white text-sm font-medium rounded-full">
                                {{ ucfirst($car->condition) }}
                            </span>
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    @if($car->images->count() > 1)
                        <div class="p-4 flex space-x-2 overflow-x-auto">
                            @foreach($car->images as $index => $image)
                                <button @click="activeImage = {{ $index }}"
                                    :class="activeImage === {{ $index }} ? 'ring-2 ring-amber-500' : 'opacity-70 hover:opacity-100'"
                                    class="w-20 h-16 rounded-lg overflow-hidden flex-shrink-0 transition-all">
                                    <img src="{{ $image->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Details -->
                <div class="bg-white rounded-2xl shadow-sm mt-6 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Vehicle Details</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Make</p>
                            <p class="font-semibold text-slate-900">{{ $car->make }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Model</p>
                            <p class="font-semibold text-slate-900">{{ $car->model }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Year</p>
                            <p class="font-semibold text-slate-900">{{ $car->year }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Mileage</p>
                            <p class="font-semibold text-slate-900">{{ $car->mileage ? number_format($car->mileage) . ' km' : 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Transmission</p>
                            <p class="font-semibold text-slate-900 capitalize">{{ $car->transmission }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Fuel Type</p>
                            <p class="font-semibold text-slate-900 capitalize">{{ $car->fuel_type }}</p>
                        </div>
                        @if($car->body_type)
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Body Type</p>
                            <p class="font-semibold text-slate-900 capitalize">{{ $car->body_type }}</p>
                        </div>
                        @endif
                        @if($car->exterior_color)
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Exterior Color</p>
                            <p class="font-semibold text-slate-900">{{ $car->exterior_color }}</p>
                        </div>
                        @endif
                        @if($car->interior_color)
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Interior Color</p>
                            <p class="font-semibold text-slate-900">{{ $car->interior_color }}</p>
                        </div>
                        @endif
                        @if($car->doors)
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Doors</p>
                            <p class="font-semibold text-slate-900">{{ $car->doors }}</p>
                        </div>
                        @endif
                        @if($car->seats)
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500">Seats</p>
                            <p class="font-semibold text-slate-900">{{ $car->seats }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-sm mt-6 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Description</h2>
                    <div class="prose prose-slate max-w-none">
                        {!! nl2br(e($car->description)) !!}
                    </div>
                </div>

                <!-- Features -->
                @if($car->features && count($car->features) > 0)
                <div class="bg-white rounded-2xl shadow-sm mt-6 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Features</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($car->features as $feature)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-slate-700">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 mt-8 lg:mt-0">
                <div class="sticky top-24 space-y-6">
                    <!-- Price Card -->
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h1 class="text-2xl font-bold text-slate-900">{{ $car->title }}</h1>
                                <p class="text-slate-500">{{ $car->year }} • {{ $car->category->name ?? '' }}</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <span class="text-3xl font-bold text-amber-600">{{ $car->formatted_price }}</span>
                            @if($car->negotiable)
                                <span class="ml-2 text-sm text-slate-500">(Negotiable)</span>
                            @endif
                        </div>

                        <!-- WhatsApp Button -->
                        <a href="{{ $car->whatsapp_link }}" target="_blank"
                            class="w-full flex items-center justify-center px-6 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition-colors mb-3">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/>
                            </svg>
                            Chat on WhatsApp
                        </a>

                        @if($car->phone_number)
                            <a href="tel:{{ $car->phone_number }}"
                                class="w-full flex items-center justify-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Call Seller
                            </a>
                        @endif

                        <!-- Favorite Button -->
                        @auth
                            <button onclick="toggleFavorite({{ $car->id }}, this)"
                                class="w-full mt-3 flex items-center justify-center px-6 py-3 border-2 border-slate-200 hover:border-red-200 hover:bg-red-50 rounded-xl transition-colors favorite-btn"
                                data-favorited="{{ $car->isFavoritedBy(auth()->user()) ? 'true' : 'false' }}">
                                <svg class="w-5 h-5 mr-2 {{ $car->isFavoritedBy(auth()->user()) ? 'text-red-500 fill-current' : 'text-slate-400' }}" 
                                    fill="{{ $car->isFavoritedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>{{ $car->isFavoritedBy(auth()->user()) ? 'Saved to Favorites' : 'Save to Favorites' }}</span>
                            </button>
                        @endauth
                    </div>

                    <!-- Location -->
                    @if($car->city)
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="font-semibold text-slate-900 mb-3">Location</h3>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-slate-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-slate-600">{{ $car->city }}@if($car->country), {{ $car->country }}@endif</span>
                        </div>
                    </div>
                    @endif

                    <!-- Stats -->
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex justify-around text-center">
                            <div>
                                <p class="text-2xl font-bold text-slate-900">{{ number_format($car->views_count) }}</p>
                                <p class="text-sm text-slate-500">Views</p>
                            </div>
                            <div class="border-l border-slate-100 pl-6">
                                <p class="text-2xl font-bold text-slate-900">{{ number_format($car->favorites_count) }}</p>
                                <p class="text-sm text-slate-500">Favorites</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Cars -->
        @if($relatedCars->count() > 0)
        <section class="mt-16">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Similar Cars</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedCars as $relatedCar)
                    <x-car-card :car="$relatedCar" />
                @endforeach
            </div>
        </section>
        @endif
    </div>

    @push('scripts')
    <script>
    function toggleFavorite(carId, btn) {
        fetch(`/favorites/${carId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            const svg = btn.querySelector('svg');
            const span = btn.querySelector('span');
            if (data.is_favorited) {
                svg.classList.add('text-red-500', 'fill-current');
                svg.classList.remove('text-slate-400');
                svg.setAttribute('fill', 'currentColor');
                span.textContent = 'Saved to Favorites';
            } else {
                svg.classList.remove('text-red-500', 'fill-current');
                svg.classList.add('text-slate-400');
                svg.setAttribute('fill', 'none');
                span.textContent = 'Save to Favorites';
            }
        });
    }
    </script>
    @endpush
</x-layouts.public>
