<form wire:submit="save">
    <div class="flex flex-col space-y-4">
        <x-field-grid class="'grid-cols-3'">
            <x-field :name="'number'" :value="$request->id" :represented-model="$request" />
            <x-field :name="'caller'" :value="$request->caller->name" :represented-model="$request" />
            <x-field :name="'created'" :value="$request->created_at" :represented-model="$request" />
            <x-field :name="'updated'" :value="$request->updated_at" :represented-model="$request" />
            <x-field :name="'category'" :value="$request->category->name" :represented-model="$request" />
            <x-field :name="'item'" :value="$request->item->name" :represented-model="$request" />
            <x-field :name="'status'" :value="$statuses" :represented-model="$request" />
            <x-field :name="'onHoldReason'" :value="$onHoldReasons" :hideable="true" :blank="true" :represented-model="$request"/>
            <x-field :name="'priority'" :value="$priorities" :represented-model="$request" />
            <x-field :name="'group'" :value="$groups" :represented-model="$request" />
            <x-field :name="'resolver'" :value="$resolvers" :represented-model="$request" :blank="true" />
            <x-field
                :name="'sla'"
                :has-permission="false"
                :percentage="$request->sla->toPercentage()"
                :value="$request->sla->minutesTillExpires() . ' minutes'"
                :represented-model="$request"
            />
        </x-field-grid>
        <x-field :hideable="true" :name="'priorityChangeReason'" :represented-model="$request" />
        <x-field :disabled="true" :name="'description'" :value="$request->description" :represented-model="$request" />
        <div class="flex flex-row justify-end">
            <x-secondary-button>Update</x-secondary-button>
        </div>
    </div>
</form>
