<a
    class="{{ $selected ? 'bg-gray-500 text-gray-100 ' : 'text-gray-100 hover:bg-gray-500'}} transition ease-in duration-75"
    href="{{ route($route) }} ">
    <div class="flex flex-row justify-between pl-4 pr-2 py-1 border-t border-gray-700 hover:cursor-pointer transition ease-in duration-75 group">
        <div>
            {{ $value }}
        </div>
        <div wire:click.prevent="starClicked" class="{{ $favorite ? '' : 'hidden group-hover:block transition ease-in duration-75' }}">
            <svg
                viewBox="0 0 24 24"
                fill="currentColor"
                stroke="currentColor"
                stroke-width="1.2"
                class="w-5 h-5 hover:scale-110"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
        </div>
    </div>
</a>
