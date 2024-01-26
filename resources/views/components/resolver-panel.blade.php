<div
    class="-mt-8 min-h-screen flex flex-col justify-between bg-white text-sm shadow-md transition-all"
    x-data="{ open: true }"
    :class="open ? 'w-64' : 'w-16'"
    x-transition
>
    <div
        class="flex flex-col sticky top-2"
        x-show="open"
    >
        <a class="{{ Route::is('resolver-panel.incidents') == route('resolver-panel.incidents') ? 'bg-slate-800 text-white hover:bg-slate-700 ' : 'hover:bg-gray-100 '}}" href="{{ route('resolver-panel.incidents') }}">
            <div class="flex flex-row pl-4 py-1 border-t border-gray-200 hover:cursor-pointer transition ease-in duration-75">
                Incidents
            </div>
        </a>
        <a class="{{ Route::is('resolver-panel.requests') == route('resolver-panel.requests') ? 'bg-slate-800 text-white hover:bg-slate-700 ' : 'hover:bg-gray-100 '}}" href="{{ route('resolver-panel.requests') }}">
            <div class="flex flex-row pl-4 py-1 border-t border-gray-200 hover:cursor-pointer transition ease-in duration-75">
                Requests
            </div>
        </a>
        <a class="{{ Route::is('resolver-panel.tasks') == route('resolver-panel.tasks') ? 'bg-slate-800 text-white hover:bg-slate-700 ' : 'hover:bg-gray-100 '}}" href="{{ route('resolver-panel.tasks') }}">
            <div class="flex flex-row pl-4 py-1 border-t border-gray-200 hover:cursor-pointer transition ease-in duration-75">
                Tasks
            </div>
        </a>
    </div>
    <div
        @click="open = !open"
        class="flex flex-row mt-auto sticky bottom-2 justify-end"
    >
        <button class="rounded-full bg-slate-800 text-white mr-2 justify-center text-center">
            <svg x-cloak x-show="open" class="h-5 w-5 rotate-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
            <svg x-cloak x-show="!open" class="h-5 w-5 -rotate-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>
