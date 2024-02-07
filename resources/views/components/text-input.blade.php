<x-field-layout
    :hidden="$field->isHidden()"
    class="{{ $field->labelPosition == FieldLabelPosition::TOP ? 'flex flex-col space-y-1' : '' }}"
>
    @if($field->hasLabel)
        <div class="flex flex-row">
            <x-field-label :value="$field->getDisplayName()" :required="$required" />
        </div>
    @endif
    <div
        wire:key="{{ rand() }}"
        class="relative {{ $field->width }}"
        x-data="{
            error: @json($errors->has($field->name)),
            disabled: @json($field->isDisabled())
        }"
        >
        @if($field->hasAnchor())<a href="{{ $field->anchor }}">@endif
            <div>
                <input
                    @click="error = false"
                    value="{{ $field->value }}"
                    class="{{ 'w-full' . ' ' . $field->style() }}"
                    :class="error ? '{{ 'ring-1 ring-red-500 ' }}' : ''"
                    wire:model.lazy="{{ $field->wireModel }}"
                    placeholder="{{ $field->placeholder }}"
                    {{ ($field->isDisabled()) ? ' disabled' : '' }}
                />
            </div>
        @if($field->hasAnchor())</a>@endif
        </div>
</x-field-layout>
