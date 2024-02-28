<div>
    <div class="flex flex-row justify-between my-2 items-center">
        <div
            @click="$dispatch('open-modal')"
            class="ml-1 hover:cursor-pointer"
        >
            <svg viewBox="0 0 24 24" fill="currentColor" class="text-blue-500 w-6 h-6">
                <path d="M18.75 12.75h1.5a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM12 6a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 6ZM12 18a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 12 18ZM3.75 6.75h1.5a.75.75 0 1 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5ZM5.25 18.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 0 1.5ZM3 12a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 3 12ZM9 3.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM12.75 12a2.25 2.25 0 1 1 4.5 0 2.25 2.25 0 0 1-4.5 0ZM9 15.75a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
            </svg>
        </div>
        <x-modal title="Personalize table">
            <div
                x-data="{ selectedColumn: @entangle('selectedColumn'), hiddenColumns: @entangle('hiddenColumns'), visibleColumns: @entangle('visibleColumns')}"
                class="flex flex-row justify-center"
            >
                <div class="flex flex-col">
                    <h6 class="pl-0.5">Hidden columns</h6>
                    <div class="flex flex-col border border-slate-400 w-32 rounded-sm mt-1 text-xs h-60">
                        <template x-for="column in hiddenColumns">
                            <div
                                @click="selectedColumn = column"
                                class="pl-2 hover:cursor-pointer font-light"
                                :class="selectedColumn === column ? 'text-white bg-blue-500' : '' "
                                x-text="column"
                            ></div>
                        </template>
                    </div>
                </div>
                <div class="flex flex-row mx-4 justify-center items-center space-x-2">
                    <button
                        wire:click="setSelectedColumnHidden"
                        class="rounded-sm bg-slate-800 text-white justify-center text-center hover:scale-110"
                    >
                        <svg x-cloak class="h-5 w-5 rotate-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button
                        wire:click="setSelectedColumnVisible"
                        class="rounded-sm bg-slate-800 text-white justify-center text-center hover:scale-110"
                    >
                        <svg x-cloak class="h-5 w-5 -rotate-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-col">
                    <h6 class="pl-0.5">Visible columns</h6>
                    <div class="flex flex-col border border-slate-400 w-32 rounded-sm mt-1 text-xs h-60 space-y-0.5">
                        <template x-for="column in visibleColumns">
                            <div
                                @click="selectedColumn = column"
                                class="pl-2 hover:cursor-pointer font-light"
                                :class="selectedColumn === column ? 'text-white bg-blue-500' : '' "
                                x-text="column"
                            ></div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="flex flex-row justify-end w-full my-4">
                <x-primary-button>Apply</x-primary-button>
            </div>
        </x-modal>
        <div>
            @if($paginate)
                <x-table-pagination :$table />
            @endif
        </div>
    </div>
    <div class="border border-slate-400 rounded-sm overflow-hidden text-xs shadow-md">
        <table class="w-full">
            <tr>
                @foreach($table->getHeaders() as $header)
                    <th class="text-left pl-3 py-1 bg-white">
                        <span
                            class="flex flex-row items-center cursor-pointer"
                            :class="'{{ $sortProperty == $header['property'] ? 'text-blue-500' : ' ' }}'"
                            wire:click="columnHeaderClicked('{{ $header['property'] }}')"
                        >
                            {{ $header['header'] }}
                            @if($sortProperty == $header['property'])
                                @if($sortOrder == SortOrder::ASCENDING)
                                    {{-- Downward --}}
                                    <div class="ml-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4 rotate-90">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                        </svg>
                                    </div>
                                @else
                                    {{-- Upward --}}
                                    <div class="ml-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4 -rotate-90">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                        </svg>
                                    </div>
                                @endif
                            @endif
                        </span>
                    </th>
                @endforeach
            </tr>
            @if($columnTextSearch)
                <tr class="border-t border-slate-300 [&>*:last-child]:border-none">
                    @foreach($table->getHeaders() as $header)
                        <td class="px-2 py-1 bg-slate-200 border-r border-slate-300">
                            <x-field :field="TableColumnSearchTextInput::make()->property($header['property'])" />
                        </td>
                    @endforeach
                </tr>
            @endif
            @foreach($table->getRows() as $rowNumber => $row)
                <tr
                    class="
                        {{ isEven($rowNumber) ? 'bg-gray-100 ' : 'bg-white ' }}
                        border-t border-slate-300 hover:bg-gray-200 ease-in transition duration-75
                    "
                >
                    @foreach($row as $cell)
                        <td class="text-left pl-3 py-1">
                            @if($cell['route'] != null)
                                <a class="underline underline-offset-2 decoration-1 hover:cursor-pointer" href="{{ $cell['route'] }}">
                            @endif
                                {{ $cell['value'] }}
                            @if($cell['route'])
                                </a>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>
    <div class="flex flex-row justify-between my-2 items-center">
        @if($paginate)<x-table-pagination :$table />@endif
    </div>
</div>
