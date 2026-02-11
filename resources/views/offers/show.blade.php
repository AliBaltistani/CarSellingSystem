<x-layouts.public :seo="['title' => $offer->title . ' | ' . config('app.name')]">
    <!-- Offer Hero -->
    <section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-amber-900 py-16">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-slate-400 hover:text-white transition-colors">Home</a></li>
                    <li><span class="text-slate-600">/</span></li>
                    <li><span class="text-amber-400">{{ $offer->title }}</span></li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Offer Info -->
                <div>
                    @if($offer->badge)
                        <span class="inline-block px-4 py-1.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-bold rounded-full mb-6 uppercase tracking-wider">
                            {{ $offer->badge }}
                        </span>
                    @endif

                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $offer->title }}</h1>

                    @if($offer->price_label)
                        <p class="text-amber-400 text-lg font-medium mb-4">{{ $offer->price_label }}</p>
                    @endif

                    @if($offer->description)
                        <p class="text-slate-300 text-lg leading-relaxed mb-8">{{ $offer->description }}</p>
                    @endif

                    <!-- Pricing -->
                    @if($offer->price_from)
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-8 border border-white/10">
                            <div class="flex items-baseline gap-3">
                                <span class="text-slate-400 text-lg">Starting from</span>
                                <span class="text-4xl font-bold text-white">AED {{ number_format($offer->price_from) }}</span>
                            </div>
                            @if($offer->price_upgrade)
                                <p class="text-slate-400 mt-2">Upgrade available from AED {{ number_format($offer->price_upgrade) }}</p>
                            @endif
                        </div>
                    @endif

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            @if($offer->price_from && $offer->price_from > 0)
                                <form action="{{ route('offers.checkout', $offer) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-lg rounded-xl transition-all shadow-lg shadow-orange-500/25 flex items-center justify-center group">
                                        <svg class="w-6 h-6 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                                        </svg>
                                        Purchase Package — AED {{ number_format($offer->price_from) }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="flex-1 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-lg rounded-xl transition-all shadow-lg shadow-orange-500/25 flex items-center justify-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Login to Purchase
                            </a>
                        @endauth

                        <a href="{{ route('cars.index') }}" class="flex-1 px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white font-semibold text-lg rounded-xl transition-all flex items-center justify-center">
                            Browse Cars
                        </a>
                    </div>
                </div>

                <!-- Right: Image / Visual -->
                <div class="relative">
                    @if($offer->image_url)
                        <img src="{{ $offer->image_url }}" alt="{{ $offer->title }}" class="w-full rounded-2xl shadow-2xl">
                    @else
                        <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-12 border border-white/10 text-center">
                            @if($offer->icon_url)
                                <img src="{{ $offer->icon_url }}" alt="" class="w-24 h-24 object-contain mx-auto mb-6">
                            @else
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                    </svg>
                                </div>
                            @endif
                            <h3 class="text-2xl font-bold text-white mb-2">{{ $offer->title }}</h3>
                            <p class="text-slate-400">Exclusive Package</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    @if($offer->features && count($offer->features) > 0)
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-slate-900 mb-8 text-center">What's Included</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($offer->features as $feature)
                    <div class="flex items-start p-6 bg-slate-50 rounded-xl border border-slate-100 hover:border-amber-200 hover:shadow-md transition-all">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-800 font-medium">{{ $feature }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Trust Badges -->
    <section class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex items-center p-6 bg-white rounded-xl shadow-sm">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-slate-900">Secure Payment</h4>
                        <p class="text-sm text-slate-500">256-bit SSL encrypted checkout</p>
                    </div>
                </div>
                <div class="flex items-center p-6 bg-white rounded-xl shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-slate-900">All Cards Accepted</h4>
                        <p class="text-sm text-slate-500">Visa, Mastercard, Amex & more</p>
                    </div>
                </div>
                <div class="flex items-center p-6 bg-white rounded-xl shadow-sm">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-slate-900">24/7 Support</h4>
                        <p class="text-sm text-slate-500">Dedicated customer support team</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Offers -->
    @if($relatedOffers->count() > 0)
    <section class="py-16 bg-gradient-to-br from-slate-900 via-slate-800 to-amber-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-white mb-8 text-center">More Exclusive Deals</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedOffers as $related)
                    <a href="{{ route('offers.show', $related) }}" class="bg-white/10 backdrop-blur-sm rounded-2xl overflow-hidden border border-white/10 hover:border-amber-500/50 transition-all group block">
                        @if($related->badge)
                            <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-bold text-center py-2 uppercase tracking-wider">
                                {{ $related->badge }}
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $related->title }}</h3>
                            @if($related->price_label)
                                <span class="text-amber-400 text-sm font-medium">{{ $related->price_label }}</span>
                            @endif
                            @if($related->description)
                                <p class="text-slate-300 text-sm mt-3">{{ Str::limit($related->description, 80) }}</p>
                            @endif
                            @if($related->price_from)
                                <div class="mt-4">
                                    <span class="text-slate-400 text-sm">From </span>
                                    <span class="text-xl font-bold text-white">AED {{ number_format($related->price_from) }}</span>
                                </div>
                            @endif
                            <div class="mt-4 text-amber-400 font-semibold group-hover:text-amber-300 transition-colors flex items-center">
                                View Deal
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</x-layouts.public>
