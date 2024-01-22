<x-field-layout :hidden="$field->isHidden()">
    @if($field->hasLabel)
        <div class="flex flex-row">
            <x-field-label :value="$field->getDisplayName()" />
            @if($required)
                <span class="text-red-500 text-sm align-top ml-1">*</span>
            @endif
        </div>
    @endif
    @if($field->hasAnchor())<a x-show="@json($field->hasAnchor())" href="{{ $field->anchor }}">@endif
            <x-field-input
                class="p-2"
                :name="$field->name"
                :value="$field->value"
                :error="$errors->has($field->name)"
                :disabled="$field->isDisabled()"
                :placeholder="$field->placeholder"
                :anchor="$field->hasAnchor()"
            />
    @if($field->hasAnchor())</a>@endif
</x-field-layout>
