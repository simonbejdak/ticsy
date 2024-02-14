<x-field-layout :field="$field">
    <input type="checkbox"
        wire:model.lazy="{{ $field->wireModel }}"
        class="{{ $field->style() }}"
        @if($field->isChecked())
            checked
        @endif
        @if($field->isDisabled())
            disabled
        @else
            wire:loading.attr="disabled"
            wire:target="save"
        @endif
    />
    <svg class="absolute top-1 left-1 hidden peer-checked:block fill-none w-4 h-4 text-gray-600" stroke-width="2.1" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
    </svg>
</x-field-layout>
