<x-layouts.public :seo="$seo ?? []">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="mt-4 text-lg text-blue-100 max-w-2xl mx-auto">{{ $page->excerpt }}</p>
            @endif
        </div>
    </div>

    <!-- FAQ Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Introduction if any -->
        @if($page->content && !str_contains($page->content, '<div class="faq-item">'))
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="prose prose-lg max-w-none">
                {!! $page->content !!}
            </div>
        </div>
        @endif

        <!-- FAQ Accordion -->
        <div class="space-y-4" x-data="{ openFaq: null }">
            @php
                // Parse FAQ items from content - expecting format: <h3>Question</h3><p>Answer</p>
                preg_match_all('/<h3[^>]*>(.*?)<\/h3>\s*<p[^>]*>(.*?)<\/p>/is', $page->content, $matches, PREG_SET_ORDER);
            @endphp

            @forelse($matches as $index => $faq)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                    class="w-full px-6 py-5 text-left flex justify-between items-center hover:bg-slate-50 transition-colors">
                    <span class="text-lg font-semibold text-slate-900">{{ strip_tags($faq[1]) }}</span>
                    <svg class="w-5 h-5 text-slate-500 transition-transform" 
                         :class="{ 'rotate-180': openFaq === {{ $index }} }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="openFaq === {{ $index }}" 
                     x-collapse
                     class="px-6 pb-5">
                    <div class="prose prose-slate max-w-none text-slate-600">
                        {!! $faq[2] !!}
                    </div>
                </div>
            </div>
            @empty
            <!-- Fallback: Just render content if no FAQ structure found -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="prose prose-lg max-w-none">
                    {!! $page->content !!}
                </div>
            </div>
            @endforelse
        </div>

        <!-- Contact CTA -->
        <div class="mt-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-8 text-center">
            <h2 class="text-2xl font-bold text-white mb-4">Still have questions?</h2>
            <p class="text-amber-100 mb-6">Can't find the answer you're looking for? Please contact our friendly team.</p>
            <a href="{{ route('page.show', 'contact-us') }}" 
               class="inline-flex items-center px-6 py-3 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contact Us
            </a>
        </div>
    </div>
</x-layouts.public>
