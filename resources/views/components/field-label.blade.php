@props(['value', 'required'])
<label {{ $attributes->merge(['class' => 'block text-xs mt-0.5 mr-4 text-gray-900']) }} disabled>
    @if($required)
        <span class="text-red-500 align-top">*</span>
    @endif
    {{ $value ?? $slot }}
</label>
