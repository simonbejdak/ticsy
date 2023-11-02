@props([
    'name' => '',
    'placeholder' => '',
    'value' => '',
    'disabled' => false,
])

<div class="space-y-1">
    <x-ticket-field-label>{{ ucfirst($name) }}</x-ticket-field-label>
    <x-ticket-field-value
        :name="$name"
        :placeholder="$placeholder"
        :value="$value"
        :disabled="$disabled"
        class="rounded-lg p-2"
    />
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
