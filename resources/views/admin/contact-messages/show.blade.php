<x-layouts.admin>
    <x-slot name="title">View Message</x-slot>

    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.contact-messages.index') }}"
           class="inline-flex items-center px-3 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Contact Message</h1>
            <p class="text-sm text-slate-500">Received {{ $contactMessage->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Message Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Sender Info -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                        {{ strtoupper(substr($contactMessage->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $contactMessage->name }}</h2>
                        <div class="flex items-center gap-4 mt-1">
                            <a href="mailto:{{ $contactMessage->email }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                                {{ $contactMessage->email }}
                            </a>
                            @if($contactMessage->phone)
                                <span class="text-slate-300">|</span>
                                <a href="tel:{{ $contactMessage->phone }}" class="text-sm text-slate-600 hover:text-slate-800">
                                    {{ $contactMessage->phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <h3 class="text-lg font-semibold text-slate-900 mb-3">{{ $contactMessage->subject }}</h3>
                    <div class="prose prose-sm max-w-none text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $contactMessage->message }}</div>
                </div>
            </div>

            <!-- Reply & Actions -->
            <div class="bg-white rounded-xl border border-slate-200 p-6" x-data="{ showReply: false }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900">Reply & Actions</h3>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 mb-4">
                    <button @click="showReply = !showReply"
                            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-lg text-sm font-medium transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        <span x-text="showReply ? 'Cancel Reply' : 'Reply via Email'"></span>
                    </button>
                    @if($contactMessage->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactMessage->phone) }}"
                           target="_blank"
                           class="inline-flex items-center px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            WhatsApp
                        </a>
                        <a href="tel:{{ $contactMessage->phone }}"
                           class="inline-flex items-center px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Call
                        </a>
                    @endif
                    <form method="POST" action="{{ route('admin.contact-messages.destroy', $contactMessage) }}"
                          onsubmit="return confirm('Delete this message permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2.5 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>

                <!-- Inline Reply Form -->
                <div x-show="showReply" x-transition class="border-t border-slate-200 pt-4" style="display: none;">
                    <form method="POST" action="{{ route('admin.contact-messages.reply', $contactMessage) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-slate-700 mb-1">To</label>
                            <input type="text" value="{{ $contactMessage->name }} <{{ $contactMessage->email }}>" disabled
                                   class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 text-slate-600">
                        </div>
                        <div class="mb-3">
                            <label for="reply_subject" class="block text-sm font-medium text-slate-700 mb-1">Subject</label>
                            <input type="text" id="reply_subject" name="subject"
                                   value="Re: {{ $contactMessage->subject }}"
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                        <div class="mb-3">
                            <label for="reply_message" class="block text-sm font-medium text-slate-700 mb-1">Message *</label>
                            <textarea id="reply_message" name="reply_message" rows="5" required
                                      placeholder="Type your reply here..."
                                      class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none">{{ old('reply_message') }}</textarea>
                            @error('reply_message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-lg text-sm font-medium transition-all shadow-sm">
                            Send Reply
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Notes -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Status & Notes</h3>
                <form method="POST" action="{{ route('admin.contact-messages.update-status', $contactMessage) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                        <select name="status"
                                class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="new" {{ $contactMessage->status === 'new' ? 'selected' : '' }}>🔵 New</option>
                            <option value="read" {{ $contactMessage->status === 'read' ? 'selected' : '' }}>🟡 Read</option>
                            <option value="replied" {{ $contactMessage->status === 'replied' ? 'selected' : '' }}>🟢 Replied</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Admin Notes</label>
                        <textarea name="admin_notes" rows="4" placeholder="Internal notes about this message..."
                                  class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none">{{ $contactMessage->admin_notes }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-medium transition-colors">
                        Update
                    </button>
                </form>
            </div>

            <!-- Details -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Details</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Status</dt>
                        <dd>{!! $contactMessage->status_badge !!}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Received</dt>
                        <dd class="text-slate-900 font-medium">{{ $contactMessage->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Time</dt>
                        <dd class="text-slate-900">{{ $contactMessage->created_at->format('h:i A') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">ID</dt>
                        <dd class="text-slate-900">#{{ $contactMessage->id }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layouts.admin>
