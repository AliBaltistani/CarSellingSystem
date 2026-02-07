<x-layouts.public :seo="$seo ?? []">
    <!-- Full Width Hero -->
    @if($page->featured_image)
    <div class="relative h-96 md:h-[500px] bg-slate-900">
        <img src="{{ $page->featured_image_url }}" alt="{{ $page->title }}" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center px-4">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="text-xl text-slate-200 max-w-3xl mx-auto">{{ $page->excerpt }}</p>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="text-xl text-slate-300 max-w-3xl mx-auto">{{ $page->excerpt }}</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Full Width Content -->
    <div class="bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="prose prose-xl max-w-none prose-headings:text-slate-900 prose-p:text-slate-600 prose-a:text-amber-600 prose-strong:text-slate-900 prose-img:rounded-xl prose-img:shadow-lg">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</x-layouts.public>
