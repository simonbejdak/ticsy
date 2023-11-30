<form wire:submit="save">
    <div class="flex flex-col space-y-4">
        <x-field-grid class="'grid-cols-3'">
            <x-field :name="'number'" :value="$ticket->id" :represented-model="$ticket" />
            <x-field :name="'caller'" :value="$ticket->user->name" :represented-model="$ticket" />
            <x-field :name="'created'" :value="$ticket->created_at" :represented-model="$ticket" />
            <x-field :name="'updated'" :value="$ticket->updated_at" :represented-model="$ticket" />
            <x-field :name="'type'" :value="$ticket->type->name" :represented-model="$ticket" />
            <x-field :name="'category'" :value="$ticket->category->name" :represented-model="$ticket" />
            <x-field :name="'item'" :value="$ticket->item->name" :represented-model="$ticket" />
            <x-field-dropdown :name="'status'" :options="$statuses" :represented-model="$ticket" />
            <x-field-dropdown :name="'onHoldReason'" :options="$onHoldReasons" :hideable="true" :blank="true" :represented-model="$ticket"/>
            <x-field-dropdown :name="'priority'" :options="$priorities" :represented-model="$ticket" />
            <x-field-dropdown :name="'group'" :options="$groups" :represented-model="$ticket" />
            <x-field-dropdown :name="'resolver'" :options="$resolvers" :represented-model="$ticket" :blank="true" />
            <x-field-bar
                :name="'sla'"
                :permission="false"
                :percentage="$ticket->sla()->toPercentage()"
                :value="$ticket->sla()->minutesTillExpires() . ' minutes'"
            />
        </x-field-grid>
        <x-field :hideable="true" :name="'priorityChangeReason'" :represented-model="$ticket" />
        <x-field :disabled="true" :name="'description'" :value="$ticket->description" :represented-model="$ticket" />
        <div class="flex flex-row justify-end">
            <x-secondary-button>Update</x-secondary-button>
        </div>
    </div>
</form>
