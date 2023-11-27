<input
    value="{{ $value }}"
    wire:model="{{ $name }}"
    {{ $disabled ? 'disabled ' : '' }}
    {!! $attributes->merge([
        'class' => $style
    ]) !!}
>
