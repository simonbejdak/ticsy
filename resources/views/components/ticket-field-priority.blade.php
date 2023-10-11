<x-ticket-select :required="$required" :disabled="$disabled" :name="$name">
    @foreach($priorities as $priority)
        <x-ticket-select-option
            :selected="$selected"
            :value="$priority"
            :text="$priority"
        />
    @endforeach
</x-ticket-select>
