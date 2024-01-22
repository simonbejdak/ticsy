<div
    wire:key="{{ rand() }}"
    class="relative"
    x-data="{
        error: @json($error),
        disabled: @json($disabled)
    }"
>
    <input
        @click="error = false"
        value="{{ $value }}"
        wire:model="{{ $name }}"
        {!! $attributes->merge([
            'class' => $style
        ]) !!}
        :class="error ? '{{ $errorStyle }}' : ''"
    />
</div>
