<x-field-layout :field="$field" :required="$required">
    <textarea
        x-autosize
        x-data="{
            error: @json($errors->has($field->name)),
            disabled: @json($field->isDisabled())
        }"
        rows="3"
        @click="error = false"
        :class="error ? '{{ 'ring-1 ring-red-500 ' }}' : ''"
        class="{{ 'w-full ' . $field->style() }}"
        wire:model.lazy="{{ $field->wireModel }}"
        placeholder="{{ $field->placeholder }}"
        {{ ($field->isDisabled()) ? ' disabled' : '' }}
    >{{ $field->value }}</textarea>
</x-field-layout>
