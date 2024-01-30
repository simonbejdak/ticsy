<x-field-layout :hidden="$field->isHidden()">
    @if($field->hasLabel)
        <div class="flex flex-row">
            <x-field-label :value="$field->getDisplayName()" />
            @if($required)
                <span class="text-red-500 text-sm align-top ml-1">*</span>
            @endif
        </div>
    @endif
    <div
        wire:key="{{ rand() }}"
        class="relative"
        x-data="{
        error: @json($errors->has($field->name)),
        disabled: @json($field->isDisabled())
    }">
        <select
            @click="error = false"
            wire:model.lazy="{{ $field->wireModel }}"
            id="{{ $field->name }}"
            class="{{ $field->style() }}"
            :class="error ? 'ring-1 ring-red-500 ' : ''"
            {{ ($field->isDisabled()) ? ' disabled' : '' }}
        >
            @if($field->blank)
                <x-field-option />
            @endif
            @foreach($field->options as $option)
                <x-field-option :value="$option['id']" :text="$option['name']"/>
            @endforeach
        </select>
        <div class="absolute top-1 right-1 pointer-events-none">
            <svg class="-mr-1 h-5 w-5" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
</x-field-layout>
