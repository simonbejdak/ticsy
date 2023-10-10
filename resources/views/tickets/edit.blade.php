<x-app-layout>
    <livewire:ticket-form :ticket="$ticket" />
{{--    <div class="flex flex-col space-y-4">--}}
{{--        <x-ticket-grid>--}}
{{--            <x-ticket-field :disabled="true" :name="'number'" :value="$ticket->id" />--}}
{{--            <x-ticket-field :disabled="true" :name="'caller'" :value="$ticket->user->name" />--}}
{{--            <x-ticket-field :disabled="true" :name="'created'" :value="$ticket->created_at" />--}}
{{--            <x-ticket-field :disabled="true" :name="'updated'" :value="$ticket->created_at" />--}}
{{--            <x-ticket-field :disabled="true" :name="'type'" :value="$ticket->type->name" />--}}
{{--            <x-ticket-field :disabled="true" :name="'category'" :value="$ticket->category->name" />--}}
{{--            <form action="{{ route('tickets.set-status', $ticket) }}" method="POST">--}}
{{--                @method('PATCH')--}}
{{--                @csrf--}}
{{--                <x-ticket-select :required="true" :disabled="!auth()->user()->can('set_status')" :name="'status'">--}}
{{--                    @foreach($statuses as $status)--}}
{{--                        <x-ticket-select-option--}}
{{--                            :selected="(isset($ticket->status->name) ? $ticket->status->name : '')"--}}
{{--                            :value="$status->id"--}}
{{--                            :text="$status->name"--}}
{{--                        />--}}
{{--                    @endforeach--}}
{{--                </x-ticket-select>--}}
{{--                <x-input-error :messages="$errors->get('status')" class="mt-2" />--}}
{{--            </form>--}}
{{--            <form action="{{ route('tickets.set-priority', $ticket) }}" method="POST">--}}
{{--                @method('PATCH')--}}
{{--                @csrf--}}
{{--                <x-ticket-select :required="true" :disabled="!auth()->user()->can('set_priority')" :name="'priority'">--}}
{{--                    @foreach($priorities as $priority)--}}
{{--                        <x-ticket-select-option--}}
{{--                            :selected="$ticket->priority"--}}
{{--                            :value="$priority"--}}
{{--                            :text="$priority"--}}
{{--                        />--}}
{{--                    @endforeach--}}
{{--                </x-ticket-select>--}}
{{--                <x-input-error :messages="$errors->get('priority')" class="mt-2" />--}}
{{--            </form>--}}
{{--            <form action="{{ route('tickets.set-resolver', $ticket) }}" method="POST">--}}
{{--                @method('PATCH')--}}
{{--                @csrf--}}
{{--                <x-ticket-select :disabled="!auth()->user()->can('set_resolver')" :name="'resolver'">--}}
{{--                    @foreach($resolvers as $resolver)--}}
{{--                        <x-ticket-select-option--}}
{{--                            :selected="(isset($ticket->resolver->name) ? $ticket->resolver->name : '')"--}}
{{--                            :value="$resolver->id"--}}
{{--                            :text="$resolver->name"--}}
{{--                        />--}}
{{--                    @endforeach--}}
{{--                </x-ticket-select>--}}
{{--                <x-input-error :messages="$errors->get('resolver')" class="mt-2" />--}}
{{--            </form>--}}
{{--        </x-ticket-grid>--}}
{{--        <x-ticket-field :disabled="true" :name="'description'" :value="$ticket->description" />--}}
{{--        <livewire:ticket-comments :ticket="$ticket" />--}}
{{--    </div>--}}
</x-app-layout>
