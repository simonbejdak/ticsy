<a href="{{route('incidents.create', ['type' => 'incident'])}}">
    <x-create-card>
        <div class="flex flex-row px-2 py-2 my-1 justify-between items-center max-w-xs">
            <div class="flex flex-row w-full justify-center">
                <div class="mr-2 flex flex-col justify-center">
                    <svg class="w-16 h-16 text-red-700" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path d="M 5 19 L 19 5 M 5 5 l 14 14" />
                    </svg>

                </div>
                <div class="flex flex-col">
                    <div class="text-md font-medium">Incident</div>
                    <div class="mt-1 text-xs">Report to us, that something does not work the way it should, we'll take a look</div>
                </div>
            </div>
        </div>
    </x-create-card>
</a>
