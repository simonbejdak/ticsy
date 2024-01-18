<x-field-layout :hidden="$field->isHidden()">
    @if($field->hasLabel)
        <x-field-label :value="$field->getDisplayName()"/>
    @endif
    <x-field-input
        class="p-2"
        :name="$field->name"
        :value="$field->value"
        :error="$errors->has($field->name)"
        :disabled="$field->disabled"
        :placeholder="$field->placeholder"
    />
</x-field-layout>
