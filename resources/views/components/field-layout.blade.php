@if(!$field->isHidden())
    <div
        wire:key="{{ rand() }}"
        class="flex
            {{ $field->labelPosition == FieldLabelPosition::TOP ? 'flex-col space-y-1' : 'flex-row justify-end w-full h-full' }}
        "
    >
        @if($field->hasLabel)
            <div class="flex flex-row">
                <x-field-label :value="$field->getLabel()" :required="$required" />
            </div>
        @endif
        <div class="relative {{ $field->width }}">
            {{ $slot }}
        </div>
    </div>
@endif

