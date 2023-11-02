<form wire:submit="create">
    <div class="font-light text-3xl">Create {{ $type->name }}</div>
    <x-ticket-grid>
        <x-ticket-field-select-category />
        <x-ticket-field-select-item :category="$category" />
        <x-ticket-field-description />
        <div class="flex flex-row justify-end">
            <x-secondary-button>Create</x-secondary-button>
        </div>
    </x-ticket-grid>
</form>
