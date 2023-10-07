<x-app-layout>
    <x-ticket-grid>
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
    </x-ticket-grid>
{{--  Comments  --}}
    <hr class="my-4 border-gray-300">
    <h2>Comments</h2>
    <div class="flex flex-col space-y-4 mb-8">
        <form action="{{route('tickets.add-comment', $ticket)}}" method="POST">
            @method('PATCH')
            @csrf
            <div class="flex flex-row mt-2 space-x-2">
                <label hidden for="body">Comment</label>
                <input placeholder="Add a comment" name="body" id="body" class="w-full p-2 rounded-md border border-gray-300" type="text">
                <x-secondary-button>Add</x-secondary-button>
            </div>
            <x-input-error :messages="$errors->get('body')" class="mt-2" />
            <hr class="border-t border-gray-300 mt-4">
        </form>
        @foreach($comments as $comment)
            <div class="flex flex-row font-light">
                <div class="border-l rounded-l-md border-2 border-black"></div>
                <div class="flex flex-col bg-white w-full px-4 py-2 rounded-r-md rounded-y-md border border-gray-300">
                    <p>{{$comment->body }}</p>
                    <div class="mt-4 text-xs">{{$comment->user->name . ' at ' . $comment->created_at->format('h:m, m.d.Y')}}</div>
                </div>
            </div>
        @endforeach
    </div>
{{--  End Comments  --}}
</x-app-layout>
