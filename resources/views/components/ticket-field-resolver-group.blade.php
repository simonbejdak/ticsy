<x-ticket-select :required="$required" :disabled="$disabled" :name="$name">
    @foreach($groups as $group)
        <x-ticket-select-option
            :value="$group->id"
            :text="$group->name"
        />
    @endforeach
</x-ticket-select>
