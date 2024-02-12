<div class="bg-white rounded-sm p-8">
    <div class="pr-48">
        <form wire:submit="save">
            <div class="flex flex-col space-y-4 mb-4">
                <x-field-grid :fields="$this->fields()->insideGrid()" />
                <x-field-grid>
                    @foreach($this->fields()->outsideGrid() as $field)
                        <x-field :$field />
                    @endforeach
                        <div class="flex flex-row justify-end col-span-2 mt-2 mb-4">
                            <x-secondary-button>Update</x-secondary-button>
                        </div>
                        @foreach($activities as $activity)
                            <x-activity-card :activity="$activity" />
                        @endforeach
                </x-field-grid>
            </div>
        </form>
    </div>
    @if(count($this->tabs()))
        <livewire:tabs :tabs="$this->tabs()" :model="$model"/>
    @endif
</div>
