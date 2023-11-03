<form wire:submit="create">
    <div class="font-light text-3xl">Create {{ $type->name }}</div>
    <x-ticket-grid>
        <x-ticket-field-select :name="'category'" :options="$categories" :blank="true" />
        <x-ticket-field-select :name="'item'" :options="$items" :blank="true" />
        <x-ticket-field :name="'description'" />
        <div class="flex flex-row justify-end">
            <x-secondary-button>Create</x-secondary-button>
        </div>
    </x-ticket-grid>
</form>
