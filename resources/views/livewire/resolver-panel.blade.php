<div
    class="relative border-r border-gray-300 bg-gray-600 font-light min-h-screen flex flex-col justify-between text-sm transition-all shadow-md"
    x-data="{ open: true }"
    :class="open ? 'w-64' : 'w-8'"
    x-transition
>
    <div x-show="open" class="flex flex-row ">
        <div
            class="flex
            {{ $selectedTab == ResolverPanelTab::ALL ? 'bg-gray-500 ' : 'text-gray-100 hover:bg-gray-500' }}
            w-1/2 border-r border-gray-700 justify-center py-2 hover:cursor-pointer text-gray-100"
            wire:click="allTabClicked"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.1" stroke="currentColor" class="w-6 h-6 {{ $selectedTab == ResolverPanelTab::ALL ? '' : 'hover:scale-110' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
        </div>
        <div
            class="flex
            {{ $selectedTab == ResolverPanelTab::FAVORITES ? 'bg-gray-500 ' : 'text-gray-100 hover:bg-gray-500' }}
            w-1/2 justify-center py-2 hover:cursor-pointer text-gray-100"
            wire:click="favoritesTabClicked"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                class="w-6 h-6 {{ $selectedTab == ResolverPanelTab::FAVORITES ? '' : 'hover:scale-110' }}">"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
        </div>
    </div>
    <div class="flex flex-col sticky top-2" x-show="open">
        @foreach($options as $option)
            <livewire:resolver-panel-option
                :option="$option"
                :selected="($option->route() == $currentRoute)"
                wire:key="{{ rand() }}"
            />
        @endforeach
    </div>
    <div
        @click="open = !open"
        class="flex flex-row mt-auto sticky bottom-2 justify-end"
    >
        <button class="rounded-sm bg-slate-800 text-white mr-2 justify-center text-center hover:scale-110">
            <svg x-cloak x-show="open" class="h-5 w-5 rotate-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
            <svg x-cloak x-show="!open" class="h-5 w-5 -rotate-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>
