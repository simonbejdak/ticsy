<nav class="mb-8 px-32 py-2 bg-white shadow-sm">
    <div class="flex justify-between h-8">
        <!-- Logo -->
        <div class="shrink-0 -mx-8 flex items-center">
            <a href="{{ route('home') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
            </a>
        </div>
        @auth
        <x-dropdown>
            <x-slot:trigger>
                <button type="button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-700 ring-inset" id="menu-button" aria-expanded="true" aria-haspopup="true">
                    {{ Auth::user()->name }}
                    <svg x-cloak x-show="!open" class="-mr-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                    <svg x-cloak x-show="open" class="-mr-1 h-5 w-5 rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot:trigger>
            <x-slot:content>
                <x-dropdown-link>
                    <div class="" role="none">
                        <form action="{{route('logout')}}" method="POST">
                            @csrf
                            <button class="text-gray-700 block w-full text-left text-sm" type="submit" tabindex="-1" id="menu-item-0">Logout</button>
                        </form>
                    </div>
                </x-dropdown-link>
            </x-slot:content>
        </x-dropdown>
        @else
            <div class="space-x-4">
                <x-nav-link href="{{route('login')}}">Login</x-nav-link>
                <x-nav-link href="{{route('register')}}">Register</x-nav-link>
            </div>
        @endauth
    </div>
</nav>
