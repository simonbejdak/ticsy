<x-field-layout :field="$field">
    <select
        x-data="{
            error: @json($errors->has($field->name)),
            disabled: @json($field->isDisabled())
        }"
        @click="error = false"
        id="{{ $field->name }}"
        class="w-full {{ $field->style() }} "
        :class="error ? 'ring-1 ring-red-500 ' : ''"
        wire:model.lazy="{{ $field->wireModel }}"
        @if($field->isDisabled())
            disabled
        @else
            wire:loading.attr="disabled"
            wire:target="save"
        @endif
    >
        @if($field->blank)
            <x-option />
        @endif
        @foreach($field->options as $option)
            <x-option :value="$option['id']" :text="$option['name']"/>
        @endforeach
    </select>
    @if(!$field->isDisabled())
        <div class="absolute top-1 right-1 pointer-events-none text-gray-600">
            <svg class="-mr-1 h-5 w-5" viewBox="0 0 20 20" aria-hidden="true">
                <path fill="currentColor" fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </div>
    @endif
</x-field-layout>
