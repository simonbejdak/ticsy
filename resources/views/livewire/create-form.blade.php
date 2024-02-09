<form wire:submit="create">
    <div class="flex flex-row w-full items-center justify-center">
        <div class="flex flex-col w-full mt-4">
            <section>
                <h2>{{ $formTitle }}</h2>
                <p class="mt-2">{{ $formDescription }}</p>
            </section>
            <section>
                <div class="flex flex-col space-y-4 mt-8">
                    @foreach($this->fields() as $field)
                        <x-field :$field />
                    @endforeach
                    <div class="flex flex-row justify-end w-4/5">
                        <x-secondary-button class="mt-2">Create</x-secondary-button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</form>
