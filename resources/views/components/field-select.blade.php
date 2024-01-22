<div
    wire:key="{{ rand() }}"
    class="relative"
    x-data="{ error: @json($error) }"
>
    <select
        @click="error = false"
        wire:model.live="{{ $name }}"
        id="{{ $name }}"
        {{ ($disabled) ? 'disabled ' : '' }}
        {!! $attributes->merge([
            'class' => $style
        ]) !!}
        :class="error ? 'ring-1 ring-red-500 ' : ''"
    >
        {{ $slot }}
    </select>
    <div class="absolute top-3 right-1 pointer-events-none">
        <svg class="-mr-1 h-5 w-5" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
        </svg>
    </div>
</div>
