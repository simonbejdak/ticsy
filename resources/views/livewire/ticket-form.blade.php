<form wire:submit="update">
    <div class="flex flex-col space-y-4">
        <x-ticket-grid>
            <x-ticket-field :disabled="true" :name="'number'" :value="$ticket->id" />
            <x-ticket-field :disabled="true" :name="'caller'" :value="$ticket->user->name" />
            <x-ticket-field :disabled="true" :name="'created'" :value="$ticket->created_at" />
            <x-ticket-field :disabled="true" :name="'updated'" :value="$ticket->created_at" />
            <x-ticket-field :disabled="true" :name="'type'" :value="$ticket->type->name" />
            <x-ticket-field :disabled="true" :name="'category'" :value="$ticket->category->name" />
            <x-ticket-field-status :ticket="$ticket" />
            <x-ticket-field-priority :ticket="$ticket" />
            <x-ticket-field-resolver-group :ticket="$ticket" />
            <x-ticket-field-resolver :ticket="$ticket" />
        </x-ticket-grid>
        <x-ticket-field :disabled="true" :name="'description'" :value="$ticket->description" />
        <div class="flex flex-row justify-end">
            <x-secondary-button>Update</x-secondary-button>
        </div>
    </div>
</form>
