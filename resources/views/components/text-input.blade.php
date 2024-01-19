<x-field-layout :hidden="$field->isHidden()">
    @if($field->hasLabel)
        <x-field-label :value="$field->getDisplayName()"/>
    @endif
    @if($field->hasAnchor())<a x-show="@json($field->hasAnchor())" href="{{ $field->anchor }}">@endif
            <x-field-input
                class="p-2"
                :name="$field->name"
                :value="$field->value"
                :error="$errors->has($field->name)"
                :disabled="$field->disabled"
                :placeholder="$field->placeholder"
                :anchor="$field->hasAnchor()"
            />
    @if($field->hasAnchor())</a>@endif
</x-field-layout>
