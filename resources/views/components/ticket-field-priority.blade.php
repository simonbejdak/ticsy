<x-ticket-select :required="$required" :disabled="$disabled" :name="$name">
    @foreach($priorities as $priority)
        <x-ticket-select-option
            :value="$priority"
            :text="$priority"
        />
    @endforeach
</x-ticket-select>
