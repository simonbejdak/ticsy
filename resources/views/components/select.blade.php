<x-field-layout :hidden="$field->isHidden()">
    @if($field->hasLabel)
        <x-field-label :value="$field->getDisplayName()"/>
    @endif
    <x-field-select :disabled="$field->disabled" :name="$field->name" :error="$errors->has($field->name)">
        @if($field->blank)
            <x-field-option />
        @endif
        @foreach($field->options as $option)
            <x-field-option :value="$option['id']" :text="$option['name']"/>
        @endforeach
        <x-field-option :value="10" :text="'Whatever'"/>
    </x-field-select>
</x-field-layout>
