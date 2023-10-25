<x-ticket-select :required="$required" :disabled="$disabled" :name="$name">
    @foreach($options as $option)
        <x-ticket-select-option
            :value="$option['id']"
            :text="$option['name']"
        />
    @endforeach
</x-ticket-select>
