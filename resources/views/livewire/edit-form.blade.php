<div>
    @if(hasTrait(\App\Traits\HasFields::class, $this))
        <form wire:submit="save">
            <div class="flex flex-col space-y-4">
                <x-field-grid class="'grid-cols-3'">
                    @foreach($this->fields()->insideGrid() as $field)
                        <x-field :field="$field" />
                    @endforeach
                </x-field-grid>
                @foreach($this->fields()->outsideGrid() as $field)
                    <x-field :field="$field" />
                @endforeach
                <div class="flex flex-row justify-end">
                    <x-secondary-button>Update</x-secondary-button>
                </div>
            </div>
        </form>
    @endif
    @if(hasTrait(\App\Traits\HasTabs::class, $this))
        <livewire:temp-tabs :tabs="$this->tabs()" :model="$model"/>
    @endif
</div>
