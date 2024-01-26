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
        wire:model.lazy="{{ $name }}"
        {!! $attributes->merge([
            'class' => $style
        ]) !!}
        :class="error ? '{{ 'ring-1 ring-red-500 ' }}' : ''"
    />
</div>
