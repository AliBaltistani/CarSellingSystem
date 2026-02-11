<x-layouts.public :seo="['title' => 'My Orders | ' . config('app.name')]">
    <section class="py-12 bg-slate-50 min-h-[70vh]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">My Orders</h1>
                    <p class="text-slate-600 mt-1">View your purchase history</p>
                </div>
                <a href="{{ route('home') }}" class="mt-4 sm:mt-0 px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-orange-500/25">
                    Browse Deals
                </a>
            </div>

            @if($orders->count() > 0)
                <!-- Orders Grid -->
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow overflow-hidden">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <!-- Left: Order Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-bold text-slate-900">{{ $order->offer->title ?? ($order->metadata['offer_title'] ?? 'Package') }}</h3>
                                            {!! $order->status_badge !!}
                                        </div>
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                                </svg>
                                                {{ $order->order_number }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $order->created_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Right: Amount -->
                                    <div class="text-right">
                                        <div class="text-2xl font-bold {{ $order->status === 'paid' ? 'text-emerald-600' : 'text-slate-900' }}">
                                            {{ $order->formatted_amount }}
                                        </div>
                                        @if($order->paid_at)
                                            <p class="text-sm text-slate-500 mt-1">Paid {{ $order->paid_at->diffForHumans() }}</p>
                                        @endif
                                    </div>
                                </div>

                                @if($order->metadata && isset($order->metadata['offer_features']) && count($order->metadata['offer_features']) > 0)
                                    <div class="mt-4 pt-4 border-t border-slate-100">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(array_slice($order->metadata['offer_features'], 0, 4) as $feature)
                                                <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-full">
                                                    <svg class="w-3 h-3 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    {{ $feature }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">No Orders Yet</h3>
                    <p class="text-slate-500 mb-6">You haven't made any purchases yet. Browse our exclusive deals!</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all">
                        View Exclusive Deals
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>
