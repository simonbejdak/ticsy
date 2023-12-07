<form wire:submit="create">
    <div class="flex flex-row w-full items-center justify-center">
        <div class="flex flex-col space-y-2 w-3/5">
            <div class="font-light text-3xl mt-16 mb-4 text-center">Create {{ $type->name }}</div>
            <x-field :name="'category'" :value="$categories" :blank="true" :modifiable="true"/>
            <x-field :name="'item'" :value="$items" :blank="true" :modifiable="true" />
            <x-field :name="'description'" :modifiable="true" />
            <div class="flex flex-row">
                <x-secondary-button class="w-full mt-2">Create</x-secondary-button>
            </div>
        </div>
    </div>
</form>
