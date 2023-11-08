<x-ticket-field-layout :hidden="$hidden">
    <x-ticket-field-label class="font-bold text-lg" :value="$displayName" />
        <x-ticket-select :disabled="$disabled" :name="$name" :styling="$styling">
            @if($blank)
                <x-ticket-select-option></x-ticket-select-option>
            @endif
            @foreach($options as $option)
                <x-ticket-select-option
                    :value="$option['id']"
                    :text="$option['name']"
                />
            @endforeach
            <x-ticket-select-option
                :value="10"
                :text="'Whatever'"
            />
        </x-ticket-select>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</x-ticket-field-layout>
