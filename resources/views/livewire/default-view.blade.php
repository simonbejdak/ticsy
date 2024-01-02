<form>
    <div class="flex flex-col space-y-4">
        <x-field-grid class="'grid-cols-3'">
            @foreach($fieldsInGrid as $field)
                <x-field-front-end :field="$field" />
            @endforeach
        </x-field-grid>
        @foreach($fieldsOutsideGrid as $field)
            <x-field-front-end :field="$field" />
        @endforeach
        <div class="flex flex-row justify-end">
            <x-secondary-button>Update</x-secondary-button>
        </div>
    </div>
</form>
