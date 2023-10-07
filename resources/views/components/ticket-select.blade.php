@props([
    'disabled' => false,
    'name',
])

<div class="flex flex-col space-y-1">
    <x-input-label class="font-bold text-lg" for="{{ $name }}">{{ ucfirst($name) }}</x-input-label>
    <div class="flex flex-row space-x-2">
        <select
            {{ ($disabled) ? 'disabled' : '' }}
            class="w-full rounded-lg border border-gray-300 {{ ($disabled) ? 'bg-gray-200' : 'bg-white hover:cursor-pointer' }} px-1 pt-2 pb-2.5"
            name="{{ $name }}"
            id="{{ $name }}"
        >
            <x-ticket-select-option></x-ticket-select-option>
            {{ $slot }}
        </select>
        @if(!$disabled)
            <x-secondary-button type="submit">Update</x-secondary-button>
        @endif
    </div>
</div>
