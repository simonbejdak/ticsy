@props([
    'name' => '',
    'disabled' => false,
    'placeholder' => '',
    'value' => '',
])

<input wire:model="{{ $name }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => (($disabled) ? 'text-gray-500 bg-gray-200 ' : 'bg-white ') . 'border border-gray-300 w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md']) !!} placeholder="{{$placeholder}}" value="{{$value}}">
