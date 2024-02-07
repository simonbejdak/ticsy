<x-field-layout
    :hidden="$field->isHidden()"
    class="{{ $field->labelPosition == FieldLabelPosition::TOP ? 'flex flex-col space-y-1' : '' }}"
>
    @if($field->hasLabel)
        <div class="flex flex-row">
            <x-field-label :value="$field->getDisplayName()" :required="$required" />
        </div>
    @endif
    <div wire:poll.60s class="flex flex-row relative justify-end text-xs {{ $field->width }} h-full bg-red-700 rounded-sm">
        <div class="bg-slate-800 h-full rounded-r-sm" style="width: {{ $field->percentage < 98 ? $field->percentage : 98 }}%"></div>
        <div class="absolute {{ $field->isPulse() ? ' animate-pulse ' : ' ' }} top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white">{{ $field->value }}</div>
    </div>
</x-field-layout>
