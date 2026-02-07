<x-layouts.admin title="Settings">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Site Settings</h1>
        <p class="text-slate-600">Manage application configuration</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        @foreach($settings ?? [] as $group => $groupSettings)
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4 capitalize">{{ str_replace('_', ' ', $group) }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($groupSettings as $setting)
                        <div class="{{ $setting->type === 'text' ? 'md:col-span-2' : '' }}">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            @if($setting->type === 'boolean')
                                <select name="settings[{{ $setting->key }}]"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                                    <option value="1" {{ $setting->value ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ !$setting->value ? 'selected' : '' }}>No</option>
                                </select>
                            @elseif($setting->type === 'text')
                                <textarea name="settings[{{ $setting->key }}]" rows="3"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">{{ $setting->value }}</textarea>
                            @else
                                <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" 
                                    name="settings[{{ $setting->key }}]" 
                                    value="{{ $setting->value }}"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg">
                Save Settings
            </button>
        </div>
    </form>
</x-layouts.admin>
