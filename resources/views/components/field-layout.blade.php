@if(!$field->isHidden())
    <div
        wire:key="{{ rand() }}"
        class="flex justify-end
            {{ $field->labelPosition == FieldLabelPosition::TOP ? 'flex-col space-y-1' : '' }}
            {{ $field->position == FieldPosition::OUTSIDE_GRID ? ' col-span-2 ' : '' }}
        "
    >
        @if($field->hasLabel && $field->labelPosition == FieldLabelPosition::TOP)
            <div class="flex flex-row">
                <x-field-label :value="$field->getLabel()" :required="$this->isFieldMarkedAsRequired($field->name)" />
            </div>
        @endif
        <div class="relative w-full">
            @if($field->hasLabel && $field->labelPosition == FieldLabelPosition::LEFT)
                <div class="absolute -left-40 w-40 text-end">
                    <x-field-label :value="$field->getLabel()" :required="$this->isFieldMarkedAsRequired($field->name)" />
                </div>
            @endif
            {{ $slot }}
        </div>
    </div>
@endif
