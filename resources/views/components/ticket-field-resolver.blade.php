<x-ticket-select :disabled="$disabled" :name="$name">
    @foreach($resolvers as $resolver)
        <x-ticket-select-option
            :value="$resolver->id"
            :text="$resolver->name"
        />
    @endforeach
</x-ticket-select>
