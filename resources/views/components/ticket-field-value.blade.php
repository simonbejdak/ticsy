@props([
    'name' => '',
    'disabled' => false,
    'placeholder' => '',
    'value' => '',
    'styling' => '',
])

<input wire:model="{{ $name }}" {{ $disabled ? 'disabled ' : '' }} {!! $attributes->merge(['class' => $styling]) !!} placeholder="{{$placeholder}}" value="{{$value}}">
