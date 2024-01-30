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
        <div
            wire:key="{{ rand() }}"
            class="relative"
            x-data="{
                error: @json($errors->has($field->name)),
                disabled: @json($field->isDisabled())
            }"
            >
                <input
                    @click="error = false"
                    value="{{ $field->value }}"
                    class="{{ $field->style() }}"
                    :class="error ? '{{ 'ring-1 ring-red-500 ' }}' : ''"
                    wire:model.lazy="{{ $field->wireModel }}"
                    placeholder="{{ $field->placeholder }}"
                    {{ ($field->isDisabled()) ? ' disabled' : '' }}
                />
            </div>
    @if($field->hasAnchor())</a>@endif
</x-field-layout>
