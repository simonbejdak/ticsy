<x-field-layout :hidden="$field->isHidden()">
    @if($field->hasLabel)
        <div class="flex flex-row">
            <x-field-label :value="$field->getDisplayName()" />
            @if($required)
                <span class="text-red-500 text-sm align-top ml-1">*</span>
            @endif
        </div>
    @endif
    <x-field-select :disabled="$field->isDisabled()" :name="$field->name" :error="$errors->has($field->name)">
        @if($field->blank)
            <x-field-option />
        @endif
        @foreach($field->options as $option)
            <x-field-option :value="$option['id']" :text="$option['name']"/>
        @endforeach
    </x-field-select>
</x-field-layout>
