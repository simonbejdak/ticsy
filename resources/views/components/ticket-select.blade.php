@props([
    'disabled' => false,
    'name' => '',
    'display-name' => '',
    'styling' => '',
    'blank' => false,
])
<div class="flex flex-col space-y-1">
    <x-ticket-field-label class="font-bold text-lg" :value="$displayName"/>
    <div class="flex flex-row space-x-2">
        <select
            wire:model.live="{{$name}}"
            id="{{$name}}"
            {{ ($disabled) ? 'disabled' : '' }}
            {!! $attributes->merge(['class' => $styling . ((!$disabled) ? 'hover:cursor-pointer' : '') . ' px-1 pt-2 pb-2.5 "']) !!}
        >
            @if($blank)
                <x-ticket-select-option></x-ticket-select-option>
            @endif
            {{ $slot }}
        </select>
    </div>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
