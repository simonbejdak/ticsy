<div>
    <form wire:submit="save">
        <div class="flex flex-col space-y-4">
            <x-field-grid class="'grid-cols-3'">
                <x-field :name="'number'" :value="$incident->id" :represented-model="$incident" />
                <x-field :name="'caller'" :value="$incident->caller->name" :represented-model="$incident" />
                <x-field :name="'created'" :value="$incident->created_at" :represented-model="$incident" />
                <x-field :name="'updated'" :value="$incident->updated_at" :represented-model="$incident" />
                <x-field :name="'category'" :value="$incident->category->name" :represented-model="$incident" />
                <x-field :name="'item'" :value="$incident->item->name" :represented-model="$incident" />
                <x-field :name="'status'" :value="$statuses" :represented-model="$incident" />
                <x-field :name="'onHoldReason'" :value="$onHoldReasons" :hideable="true" :blank="true" :represented-model="$incident"/>
                <x-field :name="'priority'" :value="$priorities" :represented-model="$incident" />
                <x-field :name="'group'" :value="$groups" :represented-model="$incident" />
                <x-field :name="'resolver'" :value="$resolvers" :represented-model="$incident" :blank="true" />
                <x-field
                    :name="'sla'"
                    :has-permission="false"
                    :percentage="$incident->sla->toPercentage()"
                    :value="$incident->sla->minutesTillExpires() . ' minutes'"
                    :represented-model="$incident"
                />
            </x-field-grid>
            <x-field :hideable="true" :name="'priorityChangeReason'" :represented-model="$incident" />
            <x-field :disabled="true" :name="'description'" :value="$incident->description" :represented-model="$incident" />
            <div class="flex flex-row justify-end">
                <x-secondary-button>Update</x-secondary-button>
            </div>
        </div>
    </form>
    <livewire:tabs :tabs="$tabs" :model="$incident"/>
</div>
