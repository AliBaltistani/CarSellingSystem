@props(['car'])

<article class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100">
    <!-- Image -->
    <div class="relative aspect-[4/3] overflow-hidden">
        <img src="{{ $car->main_image }}" alt="{{ $car->title }}" 
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        
        <!-- Featured Badge -->
        @if($car->is_featured)
            <div class="absolute top-3 left-3 px-3 py-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-semibold rounded-full">
                Featured
            </div>
        @endif

        <!-- Condition Badge -->
        <div class="absolute top-3 right-3 px-3 py-1 bg-slate-900/70 backdrop-blur-sm text-white text-xs font-medium rounded-full">
            {{ ucfirst($car->condition) }}
        </div>

        <!-- Favorite Button -->
        @auth
            <button 
                onclick="toggleFavorite({{ $car->id }}, this)"
                class="absolute bottom-3 right-3 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg hover:bg-white transition-colors favorite-btn"
                data-favorited="{{ $car->isFavoritedBy(auth()->user()) ? 'true' : 'false' }}">
                <svg class="w-5 h-5 {{ $car->isFavoritedBy(auth()->user()) ? 'text-red-500 fill-current' : 'text-slate-400' }}" 
                    fill="{{ $car->isFavoritedBy(auth()->user()) ? 'currentColor' : 'none' }}" 
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        @endauth
    </div>

    <!-- Content -->
    <div class="p-5">
        <!-- Title & Price -->
        <div class="flex justify-between items-start mb-3">
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-slate-900 truncate group-hover:text-amber-600 transition-colors">
                    <a href="{{ route('cars.show', $car) }}">
                        {{ $car->title }}
                    </a>
                </h3>
                <p class="text-sm text-slate-500 truncate">{{ $car->year }} • {{ $car->category->name ?? '' }}</p>
            </div>
            <div class="text-right ml-4">
                <span class="text-lg font-bold text-amber-600">{{ $car->formatted_price }}</span>
                @if($car->negotiable)
                    <p class="text-xs text-slate-500">Negotiable</p>
                @endif
            </div>
        </div>

        <!-- Specs Grid -->
        <div class="grid grid-cols-3 gap-3 py-3 border-y border-slate-100">
            <div class="text-center">
                <div class="text-sm font-medium text-slate-900">
                    {{ $car->mileage ? number_format($car->mileage) : '-' }}
                </div>
                <div class="text-xs text-slate-500">KM</div>
            </div>
            <div class="text-center border-x border-slate-100">
                <div class="text-sm font-medium text-slate-900 capitalize">
                    {{ $car->transmission ?? '-' }}
                </div>
                <div class="text-xs text-slate-500">Trans</div>
            </div>
            <div class="text-center">
                <div class="text-sm font-medium text-slate-900 capitalize">
                    {{ $car->fuel_type ?? '-' }}
                </div>
                <div class="text-xs text-slate-500">Fuel</div>
            </div>
        </div>

        <!-- Dynamic Attributes -->
        @php
            $cardAttributes = $car->attributeValues
                ->filter(fn($av) => $av->value !== null && $av->value !== '' && $av->attribute && $av->attribute->show_in_card && $av->attribute->is_active);
        @endphp
        @if($cardAttributes->count() > 0)
            <div class="flex flex-wrap gap-1.5 pt-3">
                @foreach($cardAttributes as $av)
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg border border-amber-100">
                        @if($av->attribute->icon)<span class="text-amber-400">{!! $av->attribute->icon !!}</span>@endif
                        <span class="text-amber-500/70">{{ $av->attribute->name }}:</span>
                        <span>{{ $av->attribute->formatValue($av->typed_value) }}</span>
                    </span>
                @endforeach
            </div>
        @endif

        <!-- Footer -->
        <div class="flex justify-between items-center mt-4">
            <div class="flex items-center text-sm text-slate-500">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $car->city ?? 'UAE' }}
            </div>
            <a href="{{ $car->whatsapp_link }}" target="_blank" 
                class="flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/>
                </svg>
                WhatsApp
            </a>
        </div>
    </div>
</article>

@once
@push('scripts')
<script>
function toggleFavorite(carId, btn) {
    fetch(`/favorites/${carId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        const svg = btn.querySelector('svg');
        if (data.is_favorited) {
            svg.classList.add('text-red-500', 'fill-current');
            svg.classList.remove('text-slate-400');
            svg.setAttribute('fill', 'currentColor');
        } else {
            svg.classList.remove('text-red-500', 'fill-current');
            svg.classList.add('text-slate-400');
            svg.setAttribute('fill', 'none');
        }
    });
}
</script>
@endpush
@endonce
