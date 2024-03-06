<x-modal title="Personalize table">
    <form wire:submit="personalize">
        <div
            x-data="{
                selectedColumn: @entangle('selectedColumn'),
                hiddenColumns: @entangle('hiddenColumns'),
                visibleColumns: @entangle('visibleColumns'),
                setSelectedColumnHidden: function () {
                    if(this.visibleColumns.includes(this.selectedColumn)){
                        this.visibleColumns = this.visibleColumns.filter(item => item !== this.selectedColumn)
                        this.hiddenColumns.push(this.selectedColumn)
                    }
                },
                setSelectedColumnVisible: function () {
                    if(this.hiddenColumns.includes(this.selectedColumn)){
                        this.hiddenColumns = this.hiddenColumns.filter(item => item !== this.selectedColumn)
                        this.visibleColumns.push(this.selectedColumn)
                    }
                },
                moveSelectedVisibleColumnUp: function () {
                    if(this.visibleColumns.includes(this.selectedColumn)){
                        const currentIndex = this.visibleColumns.indexOf(this.selectedColumn);
                        if (currentIndex > 0) {
                            this.visibleColumns.splice(currentIndex, 1);
                            this.visibleColumns.splice(currentIndex - 1, 0, this.selectedColumn);
                        }
                    }
                },
                moveSelectedVisibleColumnDown: function () {
                    if(this.visibleColumns.includes(this.selectedColumn)){
                        const currentIndex = this.visibleColumns.indexOf(this.selectedColumn);
                        if (currentIndex < this.visibleColumns.length) {
                            this.visibleColumns.splice(currentIndex, 1);
                            this.visibleColumns.splice(currentIndex + 1, 0, this.selectedColumn);
                        }
                    }
                }
            }"
            class="flex flex-row justify-center"
        >
            <div class="flex flex-col ml-2">
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
            <div class="flex flex-row mx-2 justify-center items-center space-x-1">
                <button
                    @click.prevent="setSelectedColumnHidden()"
                    class="rounded-sm bg-slate-800 text-white justify-center text-center hover:scale-110"
                >
                    <svg x-cloak class="h-5 w-5 rotate-90" viewBox="0 0 20 20" fill="currentColor"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
                <button
                    @click.prevent="setSelectedColumnVisible()"
                    class="rounded-sm bg-slate-800 text-white justify-center text-center hover:scale-110"
                >
                    <svg x-cloak class="h-5 w-5 -rotate-90" viewBox="0 0 20 20" fill="currentColor"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <div class="flex flex-row items-center">
                <div class="flex flex-col">
                    <h6 class="pl-0.5">Visible columns</h6>
                    <div
                        class="flex flex-col border border-slate-400 w-32 rounded-sm mt-1 text-xs h-60">
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
                <div class="flex flex-col ml-2 space-y-1">
                    <button
                        @click.prevent="moveSelectedVisibleColumnUp()"
                        class="rounded-sm bg-slate-800 text-white justify-center text-center hover:scale-110"
                    >
                        <svg x-cloak class="h-5 w-5 rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <button
                        @click.prevent="moveSelectedVisibleColumnDown()"
                        class="rounded-sm bg-slate-800 text-white justify-center text-center hover:scale-110"
                    >
                        <svg x-cloak class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-row justify-end w-full mt-10 my-3">
            <x-primary-button>Apply</x-primary-button>
        </div>
    </form>
</x-modal>
