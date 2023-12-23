<form wire:submit="save">
    <div class="flex flex-col space-y-4">
        <x-field-grid class="'grid-cols-3'">
            <x-field :name="'number'" :value="$task->number" :represented-model="$task" />
            <x-field :name="'caller'" :value="$task->caller->name" :represented-model="$task" />
            <x-field :name="'created'" :value="$task->created_at" :represented-model="$task" />
            <x-field :name="'updated'" :value="$task->updated_at" :represented-model="$task" />
            <x-field :name="'request'" :value="$task->request->number" :represented-model="$task" />
            <x-field :name="'category'" :value="$task->category->name" :represented-model="$task" />
            <x-field :name="'item'" :value="$task->item->name" :represented-model="$task" />
            <x-field :name="'status'" :value="$statuses" :represented-model="$task" />
            <x-field :name="'onHoldReason'" :value="$onHoldReasons" :hideable="true" :blank="true" :represented-model="$task"/>
            <x-field :name="'priority'" :value="$priorities" :represented-model="$task" />
            <x-field :name="'group'" :value="$groups" :represented-model="$task" />
            <x-field :name="'resolver'" :value="$resolvers" :represented-model="$task" :blank="true" />
            <x-field
                :name="'sla'"
                :has-permission="false"
                :percentage="$task->sla->toPercentage()"
                :value="$task->sla->minutesTillExpires() . ' minutes'"
                :represented-model="$task"
            />
        </x-field-grid>
        <x-field :hideable="true" :name="'priorityChangeReason'" :represented-model="$task" />
        <x-field :disabled="true" :name="'description'" :value="$task->description" :represented-model="$task" />
        <div class="flex flex-row justify-end">
            <x-secondary-button>Update</x-secondary-button>
        </div>
    </div>
</form>
