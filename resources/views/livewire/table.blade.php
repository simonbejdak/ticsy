<div>
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
    @if($table->isPreviousPage() || $table->isNextPage())
        <div class="flex flex-row w-full justify-end mt-2 items-center text-gray-300">
            {{-- Double Backward --}}
            <div class="px-1 py-2 {{ $table->isPreviousPage() ? 'text-slate-800 hover:bg-slate-400 hover:cursor-pointer ' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061A1.125 1.125 0 0 1 21 8.689v8.122ZM11.25 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061a1.125 1.125 0 0 1 1.683.977v8.122Z" />
                </svg>
            </div>
            {{-- Backward --}}
            <div class="px-1 py-2 {{ $table->isPreviousPage() ? 'text-slate-800 hover:bg-slate-400 hover:cursor-pointer ' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="rotate-180 w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                </svg>
            </div>
            <div>
                <x-field :field="TextInput::make('paginationIndex')->withoutLabel()" />
            </div>
            {{-- Forward --}}
            <div class="px-1 py-2 {{ $table->isNextPage() ? 'text-slate-800 hover:bg-slate-400 hover:cursor-pointer ' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                </svg>
            </div>
            {{-- Double Forward --}}
            <div class="px-1 py-2 {{ $table->isNextPage() ? 'text-slate-800 hover:bg-slate-400 hover:cursor-pointer ' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                </svg>
            </div>
        </div>
    @endif
<div>
