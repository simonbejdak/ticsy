<div>
    @if($paginate)<x-table-pagination :$table />@endif
    <div class="border border-gray-300 rounded-sm overflow-hidden text-sm">
        <table class="w-full">
            <tr>
                @foreach($table->getHeaders() as $header)
                    <th class="text-left pl-3 py-1 bg-white">
                        <span
                            class="flex flex-row items-center cursor-pointer"
                            :class="'{{ $columnToSortBy == $header['propertyPath'] ? 'text-blue-500' : ' ' }}'"
                            wire:click="columnHeaderClicked('{{ $header['propertyPath'] }}')"
                        >
                            {{ $header['header'] }}
                            @if($columnToSortBy == $header['propertyPath'])
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
            @if($columnSearch)
                <tr class="border-t border-gray-300">
                    @foreach($table->getHeaders() as $header)
                        <td class="px-2 py-1 bg-slate-200 border-r border-white">
                            <x-field :field="TableColumnSearchTextInput::make()->propertyPath($header['propertyPath'])" />
                        </td>
                    @endforeach
                </tr>
            @endif
            @foreach($table->getRows() as $rowNumber => $row)
                <tr
                    class="
                        {{ isEven($rowNumber) ? 'bg-gray-100 ' : 'bg-white ' }}
                        border-t border-gray-300 hover:bg-gray-200 ease-in transition duration-75
                    "
                >
                    @foreach($row as $cell)
                        <td class="text-left pl-3 py-1">
                            @if($cell['anchor'] != null)
                                <a class="underline underline-offset-2 decoration-1 hover:cursor-pointer" href="{{ $cell['anchor'] }}">
                            @endif
                                {{ $cell['value'] }}
                            @if($cell['anchor'])
                                </a>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>
    @if($paginate)<x-table-pagination :$table />@endif
</div>
