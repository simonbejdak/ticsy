<x-app-layout>
    <div class="flex flex-col space-y-4">
        <x-ticket-grid>
            <x-ticket-field :disabled="true" :name="'caller'" :value="$ticket->user->name" />
            <x-ticket-field :disabled="true" :name="'created'" :value="$ticket->created_at" />
            <x-ticket-field :disabled="true" :name="'updated'" :value="$ticket->created_at" />
            <x-ticket-field :disabled="true" :name="'type'" :value="$ticket->type->name" />
            <x-ticket-field :disabled="true" :name="'category'" :value="$ticket->category->name" />
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
        </x-ticket-grid>
        <x-ticket-field :disabled="true" :name="'description'" :value="$ticket->description" />
        <x-ticket-comments :comments="$comments" :ticket="$ticket" />
    </div>
</x-app-layout>
