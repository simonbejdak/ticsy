<div class="bg-white rounded-sm p-4 shadow-lg pr-28">
    @if(hasTrait(\App\Traits\HasFields::class, $this))
        <form wire:submit="save">
            <div class="flex flex-col space-y-4 mb-4">
                <x-field-grid>
                    @foreach($this->fields()->insideGrid() as $field)
                        <x-field :$field :required="$this->isFieldMarkedAsRequired($field->name)"/>
                    @endforeach
                </x-field-grid>
                <x-field-grid class="grid-cols-1 grid-rows-2 relative right-0.5">
                    @foreach($this->fields()->outsideGrid() as $field)
                        <x-field :$field :required="$this->isFieldMarkedAsRequired($field->name)" />
                    @endforeach
                </x-field-grid>
                <div class="flex flex-row justify-end">
                    <x-secondary-button>Update</x-secondary-button>
                </div>
                @if(count($activities))
                    <x-field-grid class="grid-cols-1">
                        <div class="flex flex-col space-y-2 w-full mt-4">
                            @foreach($activities as $activity)
                                <x-activity-card :activity="$activity" />
                            @endforeach
                        </div>
                    </x-field-grid>
                @endif
            </div>
        </form>
    @endif
    @if(hasTrait(\App\Traits\HasTabs::class, $this))
        <livewire:tabs :tabs="$this->tabs()" :model="$model"/>
    @endif
</div>
