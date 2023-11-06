@props([
    'placeholder' => '',
])
@if(!$hidden)
    <div class="space-y-1">
        <x-ticket-field-label :value="$displayName"/>
        <x-ticket-field-value
            :name="$name"
            :placeholder="$placeholder"
            :value="$value"
            :disabled="$disabled"
            class="p-2"
            :styling="$styling"
        />
        <x-input-error :messages="$errors->get($name)" class="mt-2" />
    </div>
@endif
