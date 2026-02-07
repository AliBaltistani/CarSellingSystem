<x-layouts.admin title="Import Attributes">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Import Attributes</h1>
        <p class="text-slate-600">Import attributes from a JSON file</p>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <h3 class="font-semibold text-blue-800 mb-2">JSON File Format</h3>
        <p class="text-sm text-blue-700 mb-2">The JSON file should contain:</p>
        <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
            <li><code class="bg-blue-100 px-1 rounded">groups</code> - Array of attribute groups</li>
            <li><code class="bg-blue-100 px-1 rounded">attributes</code> - Array of attributes with options</li>
        </ul>
        <p class="text-sm text-blue-700 mt-2">
            <strong>Tip:</strong> Export existing attributes first to see the expected format.
        </p>
    </div>

    <!-- Export Links -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Export Current Attributes</h2>
        <div class="flex gap-4">
            <a href="{{ route('admin.attributes.export') }}" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export as JSON
            </a>
            <a href="{{ route('admin.attributes.export-csv') }}" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export as CSV
            </a>
        </div>
    </div>

    <!-- Import Form -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Import Attributes</h2>
        
        <form action="{{ route('admin.attributes.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">JSON File *</label>
                <input type="file" name="file" accept=".json,application/json" required
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-amber-500">
                @error('file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Import Mode *</label>
                <div class="space-y-2">
                    <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50">
                        <input type="radio" name="mode" value="merge" checked
                            class="mt-1 w-4 h-4 text-amber-500 focus:ring-amber-500">
                        <div>
                            <span class="font-medium text-slate-900">Merge</span>
                            <p class="text-sm text-slate-600">Add new attributes and update existing ones by slug. Existing data is preserved.</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50">
                        <input type="radio" name="mode" value="replace"
                            class="mt-1 w-4 h-4 text-amber-500 focus:ring-amber-500">
                        <div>
                            <span class="font-medium text-slate-900">Replace</span>
                            <p class="text-sm text-slate-600">Replace matching attributes entirely, including all options.</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors">
                    Import Attributes
                </button>
                <a href="{{ route('admin.attributes.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-layouts.admin>
