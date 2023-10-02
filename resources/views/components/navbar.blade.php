<nav class="mb-8 px-32 py-4 bg-white shadow-sm">
    <div class="flex justify-between h-8">
        <!-- Logo -->
        <div class="shrink-0 flex items-center">
            <a href="{{ route('home') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
            </a>
        </div>
        <div class="space-x-4">
            @auth
                {{-- Logged in menu --}}
                <div
                    x-data="{ isOpen: false }"
                    class="relative inline-block text-left"
                >
                    <div>
                        <button @click="isOpen = !isOpen" type="button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-inset" id="menu-button" aria-expanded="true" aria-haspopup="true">
                            {{ Auth::user()->name }}
                            <svg x-show="!isOpen" class="-mr-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                            <svg x-show="isOpen" class="rotate-180 -mr-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="menu-button"
                        tabindex="-1"
                        x-show="isOpen"
                    >
                        <div class="py-1 my-1 hover:bg-gray-100" role="none">
                            <form action="{{route('logout')}}" method="POST">
                                @csrf
                                <button class="text-gray-700 block px-4 py-2 text-sm" type="submit" tabindex="-1" id="menu-item-0">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a class="hover:underline" href="{{route('login')}}">Login</a>
                <a class="hover:underline" href="{{route('register')}}">Register</a>
            @endauth
        </div>
    </div>
</nav>
