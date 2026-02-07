<x-layouts.admin title="Inquiry Details">
    <div class="mb-6">
        <a href="{{ route('admin.inquiries.index') }}" class="text-amber-600 hover:text-amber-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Inquiries
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Inquiry Details -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <h1 class="text-2xl font-bold text-slate-900">Inquiry Details</h1>
                <form action="{{ route('admin.inquiries.update-status', $inquiry) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()"
                        class="px-4 py-2 rounded-lg border-0 font-medium focus:ring-2 focus:ring-amber-500
                            {{ $inquiry->status === 'new' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $inquiry->status === 'contacted' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $inquiry->status === 'closed' ? 'bg-slate-100 text-slate-600' : '' }}">
                        <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ $inquiry->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="closed" {{ $inquiry->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </form>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-slate-500">Name</label>
                        <p class="font-medium text-slate-900">{{ $inquiry->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-slate-500">Email</label>
                        <p class="font-medium text-slate-900">{{ $inquiry->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-slate-500">Phone</label>
                        <p class="font-medium text-slate-900">{{ $inquiry->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-slate-500">Date</label>
                        <p class="font-medium text-slate-900">{{ $inquiry->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-slate-500">Message</label>
                    <div class="mt-2 p-4 bg-slate-50 rounded-lg">
                        <p class="text-slate-700 whitespace-pre-wrap">{{ $inquiry->message }}</p>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-100">
                    <a href="mailto:{{ $inquiry->email }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Send Email
                    </a>
                    @if($inquiry->phone)
                        <a href="tel:{{ $inquiry->phone }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Call
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Car Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Car Information</h2>
            
            @if($inquiry->car)
                <img src="{{ $inquiry->car->main_image }}" alt="" class="w-full h-40 object-cover rounded-lg mb-4">
                <h3 class="font-medium text-slate-900">{{ $inquiry->car->title }}</h3>
                <p class="text-amber-600 font-semibold mt-1">{{ $inquiry->car->formatted_price }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $inquiry->car->year }} • {{ $inquiry->car->make }} {{ $inquiry->car->model }}</p>
                
                <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                    <a href="{{ route('cars.show', $inquiry->car) }}" target="_blank" class="block text-amber-600 hover:text-amber-700 text-sm">
                        View Public Listing →
                    </a>
                    <a href="{{ route('admin.cars.edit', $inquiry->car) }}" class="block text-slate-600 hover:text-slate-800 text-sm">
                        Edit in Admin →
                    </a>
                </div>
            @else
                <p class="text-slate-500">Car no longer available</p>
            @endif
        </div>
    </div>
</x-layouts.admin>
