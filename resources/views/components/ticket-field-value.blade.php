@props([
    'disabled' => false,
])

<div {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => (($disabled) ? 'text-gray-500 ' : '') . 'border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
    {{ $slot }}
</div>
