<x-field-layout :field="$field" :required="$required">
    <div wire:poll.60s class="flex flex-row relative justify-end text-xs h-full bg-red-700 rounded-sm">
        <div class="bg-slate-800 h-full rounded-r-sm" style="width: {{ $field->percentage < 98 ? $field->percentage : 98 }}%"></div>
        <div class="absolute {{ $field->isPulse() ? ' animate-pulse ' : ' ' }} top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white">{{ $field->value }}</div>
    </div>
</x-field-layout>
