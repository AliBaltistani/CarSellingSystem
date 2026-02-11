<x-layouts.public :seo="['title' => 'Payment Cancelled | ' . config('app.name')]">
    <section class="py-20 bg-gradient-to-br from-slate-50 to-amber-50 min-h-[70vh]">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 text-center">
                <!-- Cancel Icon -->
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-slate-900 mb-4">Payment Cancelled</h1>
                <p class="text-slate-600 text-lg mb-8">Your payment was not processed. No charges were made to your account.</p>

                @if($order)
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8">
                        <p class="text-amber-800">
                            <span class="font-medium">Order {{ $order->order_number }}</span> is still pending.
                            You can try again whenever you're ready.
                        </p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('home') }}#offers" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-orange-500/25">
                        View Offers
                    </a>
                    <a href="{{ route('home') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-all">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
