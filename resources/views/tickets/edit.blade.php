<x-app-layout>
    <x-ticket-form>
        <x-ticket-field :disabled="true" :name="'type'" :value="$ticket->type->name" />
        <x-ticket-field :disabled="true" :name="'category'" :value="$ticket->category->name" />
        <x-ticket-field :disabled="true" :name="'description'" :value="$ticket->description" />
        <form action="{{ route('tickets.set-priority', $ticket) }}" method="POST">
            @method('PATCH')
            @csrf
            <x-ticket-select :disabled="!auth()->user()->can_change_priority" :submittable="auth()->user()->can_change_priority" :name="'priority'">
                @foreach($priorities as $priority)
                    <x-ticket-select-option :selected="$ticket->priority" :value="$priority" :text="$priority" />
                @endforeach
            </x-ticket-select>
        </form>
        <form action="{{ route('tickets.set-resolver', $ticket) }}" method="POST">
            @method('PATCH')
            @csrf
            <x-ticket-select :disabled="!auth()->user()->is_resolver" :submittable="auth()->user()->is_resolver" :name="'resolver'">
                @foreach($resolvers as $resolver)
                    <x-ticket-select-option
                        :selected="(isset($ticket->resolver->name) ? $ticket->resolver->name : '')"
                        :value="$resolver->id"
                        :text="$resolver->name"
                    />
                @endforeach
            </x-ticket-select>
        </form>
    </x-ticket-form>
</x-app-layout>
