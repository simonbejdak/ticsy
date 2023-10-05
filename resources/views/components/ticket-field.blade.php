@props([
    'name' => '',
    'value' => '',
    'disabled' => false,
])

<div class="space-y-1">
    <x-ticket-field-label>{{ ucfirst($name) }}</x-ticket-field-label>
    <x-ticket-field-value :disabled="'$disabled'" class="rounded-lg shadow-md p-2 bg-gray-200">
        {{ ucfirst($value) }}
    </x-ticket-field-value>
</div>
