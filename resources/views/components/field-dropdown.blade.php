<x-field-layout :hidden="$hidden">
    <x-field-label class="font-bold text-lg" :value="$displayName" />
    <x-field-select :disabled="$disabled" :name="$name" :error="$errors->has($name)">
        @if($blank)
            <x-field-option />
        @endif
        @foreach($options as $option)
            <x-field-option :value="$option['id']" :text="$option['name']"/>
        @endforeach
        <x-field-option :value="10" :text="'Whatever'"/>
    </x-field-select>
</x-field-layout>
