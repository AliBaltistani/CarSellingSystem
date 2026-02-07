@props([
    'name',
    'id' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'height' => '200px',
])

@php
    $editorId = $id ?? $name;
    $hiddenId = 'hidden_' . $editorId;
@endphp

<div class="rich-text-editor-wrapper">
    <!-- Hidden input to store the content -->
    <input type="hidden" name="{{ $name }}" id="{{ $hiddenId }}" value="{{ old($name, $value) }}">
    
    <!-- Quill Editor Container -->
    <div id="{{ $editorId }}" class="quill-editor bg-white rounded-b-lg" style="min-height: {{ $height }};">{!! old($name, $value) !!}</div>
</div>
