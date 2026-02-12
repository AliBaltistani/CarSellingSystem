<x-layouts.admin title="Edit Location">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Edit Location</h1>
        <p class="text-slate-600">Update location details</p>
    </div>

    <div class="max-w-2xl" x-data="locationEdit()">
        <form action="{{ route('admin.locations.update', $location) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Location Details</h2>

                <!-- Live Search for Updates -->
                <div class="mb-6 bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <h3 class="text-sm font-medium text-slate-900 mb-2">Update from Search</h3>
                    <p class="text-xs text-slate-500 mb-3">Search to automatically update the fields below.</p>
                    <x-forms.location-search 
                        name="" 
                        placeholder="Search to update location..."
                        :autoSave="false"
                        @location-selected="fillForm($event.detail)"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">City *</label>
                        <input type="text" name="city" x-model="form.city" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 @error('city') border-red-500 @enderror">
                        @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">State/Province</label>
                        <input type="text" name="state" x-model="form.state"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Country *</label>
                        <input type="text" name="country" x-model="form.country" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500 @error('country') border-red-500 @enderror">
                        @error('country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Display Name</label>
                        <input type="text" name="display_name" x-model="form.display_name"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Latitude</label>
                        <input type="number" name="latitude" x-model="form.latitude" step="0.0000001"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Longitude</label>
                        <input type="number" name="longitude" x-model="form.longitude" step="0.0000001"
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

    <script>
        function locationEdit() {
            return {
                form: {
                    city: '{{ old('city', $location->city) }}',
                    state: '{{ old('state', $location->state) }}',
                    country: '{{ old('country', $location->country) }}',
                    display_name: '{{ old('display_name', $location->display_name) }}',
                    latitude: '{{ old('latitude', $location->latitude) }}',
                    longitude: '{{ old('longitude', $location->longitude) }}',
                },

                fillForm(result) {
                    this.form.city = result.city || result.name || '';
                    this.form.state = result.state || '';
                    this.form.country = result.country || '';
                    this.form.display_name = result.display_name || '';
                    this.form.latitude = result.lat || '';
                    this.form.longitude = result.lon || '';
                }
            }
        }
    </script>
</x-layouts.admin>
