<x-layouts.public :seo="$seo ?? []">
    <!-- Page Header -->
    @if($page->featured_image)
    <div class="relative h-64 md:h-80 bg-slate-900">
        <img src="{{ $page->featured_image_url }}" alt="{{ $page->title }}" class="w-full h-full object-cover opacity-50">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl md:text-5xl font-bold text-white">{{ $page->title }}</h1>
            </div>
        </div>
    </div>
    @else
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="mt-4 text-lg text-blue-100 max-w-2xl mx-auto">{{ $page->excerpt }}</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Page Content with Sidebar -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="prose prose-lg max-w-none prose-headings:text-slate-900 prose-p:text-slate-600 prose-a:text-amber-600">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Links -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('cars.index') }}" class="flex items-center text-slate-600 hover:text-amber-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                Browse All Cars
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cars.create') }}" class="flex items-center text-slate-600 hover:text-amber-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                Sell Your Car
                            </a>
                        </li>
                        @foreach(\App\Models\Page::active()->inFooter()->take(5)->get() as $navPage)
                        <li>
                            <a href="{{ $navPage->url }}" class="flex items-center text-slate-600 hover:text-amber-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ $navPage->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact Box -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Need Help?</h3>
                    <p class="text-amber-100 text-sm mb-4">Our team is here to assist you with any questions.</p>
                    @if($phone = \App\Models\Setting::get('site_phone'))
                    <p class="flex items-center text-sm mb-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $phone }}
                    </p>
                    @endif
                    @if($email = \App\Models\Setting::get('site_email'))
                    <p class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $email }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
