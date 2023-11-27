<x-field-layout :hidden="$hidden">
    <x-field-label :value="$displayName"/>
    <x-field-input
        class="p-2"
        :name="$name"
        :value="$value"
        :error="$errors->has($name)"
        :disabled="$disabled"
    />
</x-field-layout>
