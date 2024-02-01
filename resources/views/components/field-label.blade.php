@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-xs mb-1 text-gray-900']) }}>
    {{ $value ?? $slot }}
</label>
