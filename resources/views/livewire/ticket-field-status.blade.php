<x-ticket-select :required="$required" :disabled="$disabled" :name="$name">
    @foreach($statuses as $status)
        <x-ticket-select-option
            :selected="$selected"
            :value="$status->id"
            :text="$status->name"
        />
    @endforeach
</x-ticket-select>
