@props([
    'disabled' => false,
    'name' => '',
    'styling' => '',
])
<select
    wire:model.live="{{$name}}"
    id="{{$name}}"
    {{ ($disabled) ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => $styling . ((!$disabled) ? ' hover:cursor-pointer' : '') . ' w-full px-1 pt-2 pb-2.5 "']) !!}
>
    {{ $slot }}
</select>
