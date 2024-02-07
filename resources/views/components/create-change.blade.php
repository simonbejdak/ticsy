<a href="{{route('incidents.create', ['type' => 'change'])}}">
    <x-card>
        <div class="flex flex-row px-2 py-2 my-1 justify-between items-center max-w-xs">
            <div class="flex flex-row w-full justify-center">
                <div class="mr-2 flex flex-col justify-center">
                    <svg class="w-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <div class="text-lg font-bold">Change</div>
                    <div class="mt-1 text-xs">Naplánujte zmenu v IT infraštruktuŕe, či už softvérovú, alebo hardvérovú</div>
                </div>
            </div>
        </div>
    </x-card>
</a>
