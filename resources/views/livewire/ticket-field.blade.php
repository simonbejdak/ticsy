<div class="flex flex-col space-y-1">
    <x-ticket-field-label :for="$name" :value="$name" />
    @if($type == 'input')
    <x-ticket-field-input :disabled="'$disabled'" class="rounded-lg shadow-md p-2 bg-gray-200" :content="$content" />
    @elseif($type == 'select')
        <div class="flex flex-row space-x-2">
            <x-ticket-select
                class="w-full rounded-lg border border-gray-300 {{ ($disabled) ? 'bg-gray-200' : 'bg-white hover:cursor-pointer' }} px-1 py-2"
                :id="$name"
                :name="$name"
            >
                @for($i = 0; $i <= count($content) - 1; $i++)
                    <x-ticket-select-option :value="$content" :content="$content[$i]"></x-ticket-select-option>
                @endfor
            </x-ticket-select>
            @if(!$disabled)
                <x-secondary-button type="submit">Update</x-secondary-button>
            @endif
        </div>
    @endif
{{--    <div class="flex flex-col space-y-2">--}}
{{--        <x-ticket-select--}}
{{--            class="w-full rounded-lg border border-gray-300 {{ ($disabled) ? 'bg-gray-200' : 'bg-white hover:cursor-pointer' }} px-1 py-2"--}}
{{--            name="{{ $name }}"--}}
{{--            id="{{ $name }}"--}}
{{--        >--}}
{{--        </x-ticket-select>--}}
{{--        @if(!$disabled)--}}
{{--            <x-secondary-button type="submit">Update</x-secondary-button>--}}
{{--        @endif--}}
{{--    </div>--}}
</div>
