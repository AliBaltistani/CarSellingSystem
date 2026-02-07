<x-layouts.admin title="Edit Location">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit Location</h1>
        <p class="text-slate-600">Update location details</p>
    </div>

    <div class="max-w-2xl">
        <form action="{{ route('admin.locations.update', $location) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Location Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">City *</label>
                        <input type="text" name="city" value="{{ old('city', $location->city) }}" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 @error('city') border-red-500 @enderror">
                        @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">State/Province</label>
                        <input type="text" name="state" value="{{ old('state', $location->state) }}"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Country *</label>
                        <input type="text" name="country" value="{{ old('country', $location->country) }}" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 @error('country') border-red-500 @enderror">
                        @error('country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Display Name</label>
                        <input type="text" name="display_name" value="{{ old('display_name', $location->display_name) }}"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Latitude</label>
                        <input type="number" name="latitude" value="{{ old('latitude', $location->latitude) }}" step="0.0000001"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Longitude</label>
                        <input type="number" name="longitude" value="{{ old('longitude', $location->longitude) }}" step="0.0000001"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.locations.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg">Update Location</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
