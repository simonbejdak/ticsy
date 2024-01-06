<x-field-layout :hidden="$field->isHidden()">
    <x-field-label :value="$field->getDisplayName()"/>
    <div class="flex flex-row relative justify-end h-full bg-red-700 rounded-md">
        <div class="bg-slate-800 h-full rounded-md" style="width: {{ $field->percentage }}%"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white">{{ $field->value }}</div>
    </div>
</x-field-layout>
