@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-xs text-gray-900']) }}>
    {{ $value ?? $slot }}
</label>
