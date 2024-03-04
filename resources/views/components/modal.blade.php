<div
    x-cloak
    x-data="{ isOpen: false }"
    @open-modal.window="isOpen = true"
>
    <div x-show="isOpen" class="relative z-10 cursor-default">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative overflow-hidden rounded-sm bg-white text-left shadow-xl">
                    <div class="flex flex-col bg-white pt-1">
                        <div class="flex flex-row justify-between align-middle px-4 pb-1 mb-5 border-b border-slate-300">
                            <h4>{{ $title }}</h4>
                            <div
                                @click="isOpen = false"
                                class="hover:cursor-pointer"
                            >
                                <svg
                                    class="absolute top-1 right-1 w-6 h-6 text-red-700"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                >
                                    <path d="M 5 19 L 19 5 M 5 5 l 14 14" />
                                </svg>
                            </div>
                        </div>
                        <div class="px-3">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
