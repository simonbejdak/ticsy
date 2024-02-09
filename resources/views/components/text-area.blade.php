<x-field-layout :field="$field">
    <textarea
        x-autosize
        x-data="{
            error: @json($errors->has($field->name)),
            disabled: @json($field->isDisabled())
        }"
        rows="3"
        @click="error = false"
        :class="error ? '{{ 'ring-1 ring-red-500 ' }}' : ''"
        class="{{ $field->style() }}"
        wire:model.lazy="{{ $field->wireModel }}"
        placeholder="{{ $field->placeholder }}"
        @if($field->isDisabled())
            disabled
        @else
            wire:loading.attr="disabled"
            wire:target="save"
        @endif
    >{{ $field->value }}</textarea>
</x-field-layout>
