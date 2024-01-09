<div>
    <form wire:submit="save">
        <div class="flex flex-col space-y-4">
            <x-field-grid class="'grid-cols-3'">
                @foreach($this->fields()->insideGrid() as $field)
                    <x-temp-field :field="$field" />
                @endforeach
            </x-field-grid>
            @foreach($this->fields()->outsideGrid() as $field)
                <x-temp-field :field="$field" />
            @endforeach
            <div class="flex flex-row justify-end">
                <x-secondary-button>Update</x-secondary-button>
            </div>
        </div>
    </form>
    <livewire:temp-tabs :tabs="$this->tabs()" :model="$model"/>
</div>
