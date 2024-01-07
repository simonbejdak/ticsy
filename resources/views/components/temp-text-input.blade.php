<x-field-layout :hidden="$field->isHidden()">
    <x-field-label :value="$field->getDisplayName()"/>
        <x-field-input
            class="p-2"
            :name="$field->name"
            :value="$field->value"
            :error="$errors->has($field->name)"
            :disabled="$field->disabled"
        />
</x-field-layout>
