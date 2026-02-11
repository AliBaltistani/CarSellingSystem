<x-layouts.admin title="Order Details">
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Order {{ $order->order_number }}</h2>
                    {!! $order->status_badge !!}
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Package</label>
                        <p class="text-slate-900 font-medium">{{ $order->offer->title ?? ($order->metadata['offer_title'] ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Amount</label>
                        <p class="text-slate-900 font-bold text-lg">{{ $order->formatted_amount }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Order Date</label>
                        <p class="text-slate-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Payment Date</label>
                        <p class="text-slate-900">{{ $order->paid_at ? $order->paid_at->format('M d, Y h:i A') : 'Not paid yet' }}</p>
                    </div>
                </div>
            </div>

            <!-- Stripe Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Payment Details</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Stripe Checkout Session</span>
                        <span class="text-sm font-mono text-slate-700">{{ $order->stripe_checkout_session_id ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Stripe Payment Intent</span>
                        <span class="text-sm font-mono text-slate-700">{{ $order->stripe_payment_intent_id ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-500">Currency</span>
                        <span class="text-sm font-medium text-slate-700">{{ strtoupper($order->currency) }}</span>
                    </div>
                </div>
            </div>

            <!-- Package Snapshot -->
            @if($order->metadata)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Package Details (at time of purchase)</h3>
                @if(isset($order->metadata['offer_description']))
                    <p class="text-slate-600 mb-4">{{ $order->metadata['offer_description'] }}</p>
                @endif
                @if(isset($order->metadata['offer_features']) && count($order->metadata['offer_features']) > 0)
                    <ul class="space-y-2">
                        @foreach($order->metadata['offer_features'] as $feature)
                            <li class="flex items-center text-sm text-slate-600">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar: Customer Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Customer</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Name</label>
                        <p class="text-slate-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Email</label>
                        <p class="text-slate-900">{{ $order->customer_email }}</p>
                    </div>
                    @if($order->customer_phone)
                    <div>
                        <label class="text-sm font-medium text-slate-500">Phone</label>
                        <p class="text-slate-900">{{ $order->customer_phone }}</p>
                    </div>
                    @endif
                    @if($order->user)
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.users.edit', $order->user) }}" class="text-amber-600 hover:text-amber-700 text-sm font-medium">
                            View User Profile →
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
