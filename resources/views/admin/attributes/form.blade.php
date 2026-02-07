<x-layouts.admin>
    <x-slot name="title">{{ $attribute->exists ? 'Edit' : 'Create' }} Attribute</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.attributes.index') }}" class="p-2 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">{{ $attribute->exists ? 'Edit' : 'Create' }} Attribute</h1>
        </div>

        <form action="{{ $attribute->exists ? route('admin.attributes.update', $attribute) : route('admin.attributes.store') }}" 
              method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6"
              x-data="{ 
                  type: '{{ old('type', $attribute->type ?? 'text') }}',
                  hasOptions() { return ['select', 'multiselect', 'color'].includes(this.type); },
                  isRange() { return ['number', 'range'].includes(this.type); }
              }">
            @csrf
            @if($attribute->exists) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-6">
                <!-- Left Column: Basic Info -->
                <div class="space-y-5">
                    <h3 class="font-semibold text-slate-900 border-b pb-2">Basic Information</h3>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Attribute Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $attribute->name) }}" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm"
                            placeholder="e.g., Engine Capacity, Warranty">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-slate-700 mb-1">Slug</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $attribute->slug) }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm font-mono"
                            placeholder="Auto-generated">
                    </div>

                    <!-- Group -->
                    <div>
                        <label for="attribute_group_id" class="block text-sm font-medium text-slate-700 mb-1">Group</label>
                        <select id="attribute_group_id" name="attribute_group_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
                            <option value="">No Group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('attribute_group_id', $attribute->attribute_group_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 mb-1">Field Type *</label>
                        <select id="type" name="type" x-model="type" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $attribute->type) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Order & Status -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="order" class="block text-sm font-medium text-slate-700 mb-1">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $attribute->order ?? 0) }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
                        </div>
                        <div class="flex items-end gap-3 pb-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $attribute->is_active ?? true) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                <span class="text-sm text-slate-700">Active</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_required" value="1" {{ old('is_required', $attribute->is_required) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                <span class="text-sm text-slate-700">Required</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Display & Formatting -->
                <div class="space-y-5">
                    <h3 class="font-semibold text-slate-900 border-b pb-2">Display Settings</h3>

                    <!-- Display Checkboxes -->
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100">
                            <input type="checkbox" name="show_in_filters" value="1" {{ old('show_in_filters', $attribute->show_in_filters) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            <div>
                                <span class="text-sm font-medium text-slate-700">Filters</span>
                                <p class="text-xs text-slate-500">Show in search filters</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100">
                            <input type="checkbox" name="show_in_card" value="1" {{ old('show_in_card', $attribute->show_in_card) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            <div>
                                <span class="text-sm font-medium text-slate-700">Card</span>
                                <p class="text-xs text-slate-500">Show in listing cards</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100">
                            <input type="checkbox" name="show_in_details" value="1" {{ old('show_in_details', $attribute->show_in_details ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            <div>
                                <span class="text-sm font-medium text-slate-700">Details</span>
                                <p class="text-xs text-slate-500">Show in detail page</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100">
                            <input type="checkbox" name="show_in_comparison" value="1" {{ old('show_in_comparison', $attribute->show_in_comparison) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            <div>
                                <span class="text-sm font-medium text-slate-700">Compare</span>
                                <p class="text-xs text-slate-500">Show in comparison</p>
                            </div>
                        </label>
                    </div>

                    <!-- Prefix/Suffix -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="prefix" class="block text-sm font-medium text-slate-700 mb-1">Prefix</label>
                            <input type="text" id="prefix" name="prefix" value="{{ old('prefix', $attribute->prefix) }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm"
                                placeholder="e.g., AED">
                        </div>
                        <div>
                            <label for="suffix" class="block text-sm font-medium text-slate-700 mb-1">Suffix</label>
                            <input type="text" id="suffix" name="suffix" value="{{ old('suffix', $attribute->suffix) }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm"
                                placeholder="e.g., km, hp, L">
                        </div>
                    </div>

                    <!-- Placeholder & Help -->
                    <div>
                        <label for="placeholder" class="block text-sm font-medium text-slate-700 mb-1">Placeholder</label>
                        <input type="text" id="placeholder" name="placeholder" value="{{ old('placeholder', $attribute->placeholder) }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm"
                            placeholder="Placeholder text for input field">
                    </div>
                    <div>
                        <label for="help_text" class="block text-sm font-medium text-slate-700 mb-1">Help Text</label>
                        <input type="text" id="help_text" name="help_text" value="{{ old('help_text', $attribute->help_text) }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm"
                            placeholder="Help text shown below the field">
                    </div>
                </div>
            </div>

            <!-- Range Settings -->
            <div x-show="isRange()" x-cloak class="mt-6 pt-6 border-t border-slate-200">
                <h3 class="font-semibold text-slate-900 mb-4">Range Settings</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="min_value" class="block text-sm font-medium text-slate-700 mb-1">Minimum Value</label>
                        <input type="number" id="min_value" name="min_value" value="{{ old('min_value', $attribute->min_value) }}" step="any"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label for="max_value" class="block text-sm font-medium text-slate-700 mb-1">Maximum Value</label>
                        <input type="number" id="max_value" name="max_value" value="{{ old('max_value', $attribute->max_value) }}" step="any"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
                    </div>
                    <div>
                        <label for="step" class="block text-sm font-medium text-slate-700 mb-1">Step</label>
                        <input type="number" id="step" name="step" value="{{ old('step', $attribute->step) }}" step="any"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm">
                    </div>
                </div>
            </div>

            <!-- Options (for select/multiselect/color) -->
            <div x-show="hasOptions()" x-cloak class="mt-6 pt-6 border-t border-slate-200" 
                 x-data="{ options: {{ json_encode(old('options', $attribute->options->map(fn($o) => ['id' => $o->id, 'label' => $o->label, 'value' => $o->value, 'color' => $o->color, 'is_default' => $o->is_default])->toArray()) ?: '[]') }} }">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-slate-900">Options</h3>
                    <button type="button" @click="options.push({ id: null, label: '', value: '', color: '', is_default: false })"
                        class="text-sm text-amber-600 hover:text-amber-700 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Option
                    </button>
                </div>

                <div class="space-y-2">
                    <template x-for="(option, index) in options" :key="index">
                        <div class="flex gap-2 items-center bg-slate-50 p-3 rounded-lg">
                            <input type="hidden" :name="'options['+index+'][id]'" :value="option.id">
                            <input type="text" :name="'options['+index+'][label]'" x-model="option.label" placeholder="Label" required
                                class="flex-1 px-3 py-2 border border-slate-300 rounded text-sm">
                            <input type="text" :name="'options['+index+'][value]'" x-model="option.value" placeholder="Value (auto-gen)"
                                class="w-32 px-3 py-2 border border-slate-300 rounded text-sm font-mono">
                            <template x-if="type === 'color'">
                                <input type="color" :name="'options['+index+'][color]'" x-model="option.color"
                                    class="w-10 h-10 rounded border border-slate-300 cursor-pointer">
                            </template>
                            <label class="flex items-center gap-1">
                                <input type="checkbox" :name="'options['+index+'][is_default]'" x-model="option.is_default" value="1"
                                    class="w-4 h-4 rounded border-slate-300 text-amber-600">
                                <span class="text-xs text-slate-500">Default</span>
                            </label>
                            <button type="button" @click="options.splice(index, 1)" class="p-1 text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
                <p x-show="options.length === 0" class="text-sm text-slate-500 mt-2">No options added. Click "Add Option" to create choices.</p>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-end gap-4">
                <a href="{{ route('admin.attributes.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-900">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                    {{ $attribute->exists ? 'Update' : 'Create' }} Attribute
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
