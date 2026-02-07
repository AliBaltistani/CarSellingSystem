<x-layouts.public :seo="$seo ?? []">
    <!-- Page Header -->
    @if($page->featured_image)
    <div class="relative h-64 md:h-80 bg-slate-900">
        <img src="{{ $page->featured_image_url }}" alt="{{ $page->title }}" class="w-full h-full object-cover opacity-50">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-3xl md:text-5xl font-bold text-white">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="mt-4 text-lg text-slate-200 max-w-2xl mx-auto px-4">{{ $page->excerpt }}</p>
                @endif
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

    <!-- Page Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
            <div class="prose prose-lg max-w-none prose-headings:text-slate-900 prose-p:text-slate-600 prose-a:text-amber-600 prose-strong:text-slate-900">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</x-layouts.public>
