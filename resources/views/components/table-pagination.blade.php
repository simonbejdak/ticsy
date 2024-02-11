@if($table->hasPreviousPage() || $table->hasNextPage())
    <div class="flex flex-row w-full justify-end my-2 items-center text-gray-300">
        {{-- Double Backwards --}}
        <div
            {{ !$table->hasPreviousPage() ? 'disabled' : '' }}
            class="p-1 rounded-md {{ $table->hasPreviousPage() ? 'text-slate-800 hover:bg-slate-400 hover:cursor-pointer ' : '' }}"
            wire:click="doubleBackwardsClicked"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061A1.125 1.125 0 0 1 21 8.689v8.122ZM11.25 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061a1.125 1.125 0 0 1 1.683.977v8.122Z"/>
            </svg>
        </div>
        {{-- Backwards --}}
        <div
            {{ !$table->hasPreviousPage() ? 'disabled' : '' }}
            class="p-1 rounded-md {{ $table->hasPreviousPage() ? 'text-slate-800 hover:bg-slate-400 hover:cursor-pointer ' : '' }}"
            wire:click="backwardsClicked"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="rotate-180 w-3 h-3">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z"/>
            </svg>
        </div>
        <div class="w-12">
            <x-field :field="TablePaginationIndexTextInput::make()"/>
        </div>
        <div class="text-black text-xs px-1 ml-4">
            {{ 'to ' . $table->to() . ' from ' . $table->count }}
        </div>
        {{-- Forward --}}
        <div
            {{ !$table->hasNextPage() ? 'disabled' : '' }}
            class="p-1 rounded-md {{ $table->hasNextPage() ? 'text-slate-800 hover:bg-slate-400 hover:cursor-pointer ' : '' }}"
            wire:click="forwardClicked"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-3 h-3">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z"/>
            </svg>
        </div>
        {{-- Double Forward --}}
        <div
            {{ !$table->hasNextPage() ? 'disabled' : '' }}
            class="p-1 rounded-md {{ $table->hasNextPage() ? 'text-slate-800 hover:bg-slate-400 hover:cursor-pointer ' : '' }}"
            wire:click="doubleForwardClicked"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z"/>
            </svg>
        </div>
    </div>
@endif
