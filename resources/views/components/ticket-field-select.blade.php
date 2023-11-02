<x-ticket-select :blank="$blank" :disabled="$disabled" :name="$name">
    @foreach($options as $option)
        <x-ticket-select-option
            :value="$option['id']"
            :text="$option['name']"
        />
    @endforeach
        <x-ticket-select-option
            :value="10"
            :text="'whatever'"
        />
</x-ticket-select>
