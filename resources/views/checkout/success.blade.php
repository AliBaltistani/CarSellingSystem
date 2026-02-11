<x-layouts.public :seo="['title' => 'Payment Successful | ' . config('app.name')]">
    <section class="py-20 bg-gradient-to-br from-slate-50 to-emerald-50 min-h-[70vh]">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 text-center">
                <!-- Success Animation -->
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center animate-bounce">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-slate-900 mb-4">Payment Successful!</h1>
                <p class="text-slate-600 text-lg mb-8">Thank you for your purchase. Your order has been confirmed.</p>

                @if($order)
                    <!-- Order Details -->
                    <div class="bg-slate-50 rounded-xl p-6 mb-8 text-left">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Order Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                <span class="text-slate-500">Order Number</span>
                                <span class="font-semibold text-slate-900">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                <span class="text-slate-500">Package</span>
                                <span class="font-semibold text-slate-900">{{ $order->offer->title ?? ($order->metadata['offer_title'] ?? 'N/A') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                <span class="text-slate-500">Amount Paid</span>
                                <span class="font-bold text-emerald-600 text-lg">{{ $order->formatted_amount }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-200">
                                <span class="text-slate-500">Status</span>
                                {!! $order->status_badge !!}
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-500">Date</span>
                                <span class="text-slate-700">{{ $order->paid_at ? $order->paid_at->format('M d, Y h:i A') : $order->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('orders.my-orders') }}" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-orange-500/25">
                        View My Orders
                    </a>
                    <a href="{{ route('home') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-all">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
