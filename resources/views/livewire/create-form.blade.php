<form wire:submit="create">
    <div class="flex flex-row w-full items-center justify-center">
        <div class="flex flex-col space-y-2 w-3/5">
            <div class="font-light text-3xl mt-16 mb-4 text-center">{{ $formName }}</div>
            @foreach($this->fields() as $field)
                <x-field :$field :required="$this->isFieldMarkedAsRequired($field->name)"/>
            @endforeach
            <div class="flex flex-row">
                <x-secondary-button class="w-full mt-2">Create</x-secondary-button>
            </div>
        </div>
    </div>
</form>
