<x-layouts.public :seo="$seo ?? []">
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
        .gallery-fullscreen { position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.95); display: flex; align-items: center; justify-content: center; }
        .sidebar-glass { background: rgba(255,255,255,0.9); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.6); }
        .spec-card { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); transition: all 0.3s; }
        .spec-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px -5px rgba(0,0,0,0.08); }
        .feature-pill { @apply inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 text-emerald-700 text-sm font-medium rounded-full border border-emerald-100 transition-all; }
        .feature-pill:hover { @apply bg-emerald-100 shadow-sm; }
        [x-cloak] { display: none !important; }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="carShowPage()" x-cloak>
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm animate-fade-in-up">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="text-slate-500 hover:text-amber-600 transition-colors">Home</a></li>
                <li class="text-slate-300">/</li>
                <li><a href="{{ route('cars.index') }}" class="text-slate-500 hover:text-amber-600 transition-colors">Cars</a></li>
                <li class="text-slate-300">/</li>
                <li class="text-slate-900 font-medium truncate max-w-[200px]">{{ $car->title }}</li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Image Gallery -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden animate-fade-in-up" x-data="{ activeImage: 0, fullscreen: false, totalImages: {{ $car->images->count() ?: 1 }} }">
                    <!-- Main Image -->
                    <div class="aspect-[4/3] bg-slate-100 relative cursor-pointer group" @click="fullscreen = true">
                        @if($car->images->count() > 0)
                            @foreach($car->images as $index => $image)
                                <img x-show="activeImage === {{ $index }}" src="{{ $image->url }}" alt="{{ $car->title }}"
                                    class="w-full h-full object-cover" x-transition.opacity.duration.300ms>
                            @endforeach
                        @else
                            <img src="{{ $car->main_image }}" alt="{{ $car->title }}" class="w-full h-full object-cover">
                        @endif

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full flex items-center gap-2 text-sm font-medium text-slate-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                Click to expand
                            </div>
                        </div>

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex space-x-2">
                            @if($car->is_featured)
                                <span class="px-3 py-1.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-full shadow-lg shadow-orange-500/25">Featured</span>
                            @endif
                            <span class="px-3 py-1.5 bg-slate-900/70 backdrop-blur-sm text-white text-sm font-medium rounded-full">{{ ucfirst($car->condition) }}</span>
                        </div>

                        <!-- Image Counter -->
                        @if($car->images->count() > 1)
                        <div class="absolute bottom-4 right-4 px-3 py-1.5 bg-slate-900/70 backdrop-blur-sm text-white text-sm font-medium rounded-full">
                            <span x-text="(activeImage + 1) + ' / ' + totalImages"></span>
                        </div>
                        @endif

                        <!-- Prev/Next Arrows -->
                        @if($car->images->count() > 1)
                        <button @click.stop="activeImage = (activeImage - 1 + totalImages) % totalImages" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white">
                            <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click.stop="activeImage = (activeImage + 1) % totalImages" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white">
                            <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if($car->images->count() > 1)
                        <div class="p-4 flex space-x-2 overflow-x-auto scrollbar-thin">
                            @foreach($car->images as $index => $image)
                                <button @click="activeImage = {{ $index }}"
                                    :class="activeImage === {{ $index }} ? 'ring-2 ring-amber-500 ring-offset-2 opacity-100' : 'opacity-60 hover:opacity-90'"
                                    class="w-20 h-16 rounded-lg overflow-hidden flex-shrink-0 transition-all duration-200">
                                    <img src="{{ $image->thumbnail_url }}" alt="" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Fullscreen Gallery -->
                    <template x-teleport="body">
                        <div x-show="fullscreen" x-transition.opacity.duration.300ms class="gallery-fullscreen" @keydown.escape.window="fullscreen = false" @keydown.left.window="if(fullscreen) activeImage = (activeImage - 1 + totalImages) % totalImages" @keydown.right.window="if(fullscreen) activeImage = (activeImage + 1) % totalImages">
                            <button @click="fullscreen = false" class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center transition-colors z-10">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <div class="absolute top-6 left-6 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white text-sm font-medium"><span x-text="(activeImage + 1) + ' / ' + totalImages"></span></div>
                            <button @click="activeImage = (activeImage - 1 + totalImages) % totalImages" class="absolute left-4 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center transition-colors">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button @click="activeImage = (activeImage + 1) % totalImages" class="absolute right-4 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center transition-colors">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                            @foreach($car->images as $index => $image)
                                <img x-show="activeImage === {{ $index }}" src="{{ $image->url }}" alt="{{ $car->title }}" class="max-w-[90vw] max-h-[85vh] object-contain rounded-lg" x-transition.opacity.duration.200ms>
                            @endforeach
                        </div>
                    </template>
                </div>

                <!-- Vehicle Details -->
                <div class="bg-white rounded-2xl shadow-sm p-6" x-intersect.once="$el.classList.add('animate-fade-in-up')">
                    <h2 class="text-xl font-bold text-slate-900 mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Vehicle Details
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Make</p><p class="font-semibold text-slate-900">{{ $car->make }}</p></div>
                        <div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Model</p><p class="font-semibold text-slate-900">{{ $car->model }}</p></div>
                        <div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Year</p><p class="font-semibold text-slate-900">{{ $car->year }}</p></div>
                        <div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Mileage</p><p class="font-semibold text-slate-900">{{ $car->mileage ? number_format($car->mileage) . ' km' : 'N/A' }}</p></div>
                        <div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Transmission</p><p class="font-semibold text-slate-900 capitalize">{{ $car->transmission }}</p></div>
                        <div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Fuel Type</p><p class="font-semibold text-slate-900 capitalize">{{ $car->fuel_type }}</p></div>
                        @if($car->body_type)<div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Body Type</p><p class="font-semibold text-slate-900 capitalize">{{ $car->body_type }}</p></div>@endif
                        @if($car->exterior_color)<div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Exterior Color</p><p class="font-semibold text-slate-900">{{ $car->exterior_color }}</p></div>@endif
                        @if($car->interior_color)<div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Interior Color</p><p class="font-semibold text-slate-900">{{ $car->interior_color }}</p></div>@endif
                        @if($car->doors)<div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Doors</p><p class="font-semibold text-slate-900">{{ $car->doors }}</p></div>@endif
                        @if($car->seats)<div class="spec-card p-4 rounded-xl"><p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Seats</p><p class="font-semibold text-slate-900">{{ $car->seats }}</p></div>@endif
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-sm p-6" x-intersect.once="$el.classList.add('animate-fade-in-up')" x-data="{ expanded: false, needsExpand: false }" x-init="$nextTick(() => { needsExpand = $refs.descContent && $refs.descContent.scrollHeight > 200; })">
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Description
                    </h2>
                    <div class="relative">
                        <div x-ref="descContent" class="prose prose-slate max-w-none text-slate-700 leading-relaxed" :class="!expanded && needsExpand ? 'max-h-[200px] overflow-hidden' : ''" :style="!expanded && needsExpand ? 'mask-image: linear-gradient(to bottom, black 60%, transparent 100%); -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%)' : ''">
                            {!! nl2br(e($car->description)) !!}
                        </div>
                        <button x-show="needsExpand" @click="expanded = !expanded" class="mt-3 text-amber-600 hover:text-amber-700 font-semibold text-sm flex items-center gap-1 transition-colors">
                            <span x-text="expanded ? 'Show Less' : 'Read More'"></span>
                            <svg class="w-4 h-4 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Features -->
                @if($car->features && count($car->features) > 0)
                <div class="bg-white rounded-2xl shadow-sm p-6" x-intersect.once="$el.classList.add('animate-fade-in-up')">
                    <h2 class="text-xl font-bold text-slate-900 mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Features
                    </h2>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach($car->features as $feature)
                            <span class="feature-pill">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Custom Specifications (Dynamic Attributes) -->
                @php
                    $groupedAttributes = $car->attributeValues
                        ->filter(fn($av) => $av->value !== null && $av->value !== '' && $av->attribute && $av->attribute->show_in_details && $av->attribute->is_active)
                        ->groupBy(fn($av) => $av->attribute->group->name ?? 'General');
                @endphp
                @if($groupedAttributes->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm p-6" x-intersect.once="$el.classList.add('animate-fade-in-up')">
                    <h2 class="text-xl font-bold text-slate-900 mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Custom Specifications
                    </h2>
                    @foreach($groupedAttributes as $groupName => $values)
                        <div class="mb-6 last:mb-0">
                            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="h-px flex-1 bg-slate-100"></span>
                                {{ $groupName }}
                                <span class="h-px flex-1 bg-slate-100"></span>
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($values as $attrValue)
                                    <div class="spec-card p-4 rounded-xl">
                                        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">
                                            @if($attrValue->attribute->icon)<span class="mr-1 inline-flex">{!! $attrValue->attribute->icon !!}</span>@endif
                                            {{ $attrValue->attribute->name }}
                                        </p>
                                        <p class="font-semibold text-slate-900">{{ $attrValue->attribute->formatValue($attrValue->value) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 mt-8 lg:mt-0">
                <div class="sticky top-24 space-y-5">
                    <!-- Price Card -->
                    <div class="sidebar-glass rounded-2xl shadow-lg p-6 animate-fade-in-up">
                        <div class="mb-4">
                            <h1 class="text-2xl font-bold text-slate-900 leading-tight">{{ $car->title }}</h1>
                            <p class="text-slate-500 mt-1">{{ $car->year }} • {{ $car->category->name ?? '' }}</p>
                        </div>

                        <div class="mb-6 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                            <span class="text-3xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">{{ $car->formatted_price }}</span>
                            @if($car->negotiable)
                                <span class="ml-2 text-sm text-amber-600 font-medium">(Negotiable)</span>
                            @endif
                        </div>

                        <!-- WhatsApp Button -->
                        <a href="{{ $car->whatsapp_link }}" target="_blank"
                            class="w-full flex items-center justify-center px-6 py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 mb-3 group">
                            <svg class="w-6 h-6 mr-2 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
                            Chat on WhatsApp
                        </a>

                        @if($car->phone_number)
                            <a href="tel:{{ $car->phone_number }}"
                                class="w-full flex items-center justify-center px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-all mb-3 group">
                                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Call Seller
                            </a>
                        @endif

                        <!-- Share Button -->
                        <button @click="shareLink()" class="w-full flex items-center justify-center px-6 py-3 border-2 border-slate-200 hover:border-amber-200 hover:bg-amber-50 rounded-xl transition-all mb-3 group">
                            <svg class="w-5 h-5 mr-2 text-slate-400 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            <span x-text="linkCopied ? '✓ Link Copied!' : 'Share Listing'"></span>
                        </button>

                        <!-- Favorite Button -->
                        @auth
                            <button onclick="toggleFavorite({{ $car->id }}, this)"
                                class="w-full flex items-center justify-center px-6 py-3 border-2 border-slate-200 hover:border-red-200 hover:bg-red-50 rounded-xl transition-all favorite-btn group"
                                data-favorited="{{ $car->isFavoritedBy(auth()->user()) ? 'true' : 'false' }}">
                                <svg class="w-5 h-5 mr-2 {{ $car->isFavoritedBy(auth()->user()) ? 'text-red-500 fill-current' : 'text-slate-400' }} group-hover:scale-110 transition-transform"
                                    fill="{{ $car->isFavoritedBy(auth()->user()) ? 'currentColor' : 'none' }}"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>{{ $car->isFavoritedBy(auth()->user()) ? 'Saved to Favorites' : 'Save to Favorites' }}</span>
                            </button>
                        @endauth
                    </div>

                    <!-- Location -->
                    @if($car->city)
                    <div class="sidebar-glass rounded-2xl shadow-sm p-6" x-intersect.once="$el.classList.add('animate-fade-in-up')">
                        <h3 class="font-semibold text-slate-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Location
                        </h3>
                        <p class="text-slate-600">{{ $car->city }}@if($car->country), {{ $car->country }}@endif</p>
                    </div>
                    @endif

                    <!-- Stats -->
                    <div class="sidebar-glass rounded-2xl shadow-sm p-6" x-intersect.once="$el.classList.add('animate-fade-in-up')">
                        <div class="flex justify-around text-center">
                            <div>
                                <p class="text-2xl font-bold text-slate-900">{{ number_format($car->views_count) }}</p>
                                <p class="text-sm text-slate-500">Views</p>
                            </div>
                            <div class="border-l border-slate-200 pl-6">
                                <p class="text-2xl font-bold text-slate-900">{{ number_format($car->favorites_count) }}</p>
                                <p class="text-sm text-slate-500">Favorites</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Cars - Horizontal Carousel -->
        @if($relatedCars->count() > 0)
        <section class="mt-16" x-intersect.once="$el.classList.add('animate-fade-in-up')">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-slate-900">Similar Cars</h2>
                <a href="{{ route('cars.index', ['make' => $car->make]) }}" class="text-amber-600 hover:text-amber-700 font-semibold text-sm flex items-center">
                    View All <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="flex gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-thin" style="-webkit-overflow-scrolling: touch;">
                @foreach($relatedCars as $relatedCar)
                    <div class="flex-shrink-0 w-72 snap-start">
                        <x-car-card :car="$relatedCar" />
                    </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>

    @push('scripts')
    <script>
    function carShowPage() {
        return {
            linkCopied: false,
            shareLink() {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    this.linkCopied = true;
                    setTimeout(() => this.linkCopied = false, 2000);
                });
            }
        };
    }

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
