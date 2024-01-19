<input
    value="{{ $value }}"
    wire:model="{{ $name }}"
    {!! $attributes->merge([
        'class' => $style
    ]) !!}
/>
