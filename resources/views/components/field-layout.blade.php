@if(!$field->isHidden())
    <div
        class="flex {{ $field->labelPosition == FieldLabelPosition::TOP ? 'flex-col space-y-1' : 'flex-row justify-end w-full h-full' }}"
    >
        @if($field->hasLabel)
            <div class="flex flex-row">
                <x-field-label :value="$field->getDisplayName()" :required="$required" />
            </div>
        @endif
        {{ $slot }}
    </div>
@endif

