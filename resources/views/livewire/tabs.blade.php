<div class="flex flex-col mb-4">
    <div class="flex flex-row">
        @foreach($tabs as $tab)
            <div
                wire:click="setViewedTab('{{ $tab }}')"
                class="{{ $viewedTab === $tab ? 'bg-white border-t border-x border-gray-300 rounded-t-lg' : '' }} z-10 px-3 pt-1 pb-2 hover:cursor-pointer"
            >{{ ucfirst($tab) }}</div>
        @endforeach
    </div>
    <div class="bg-white border border-gray-300 -mt-1 p-4">
{{--        Due to Livewire nature we need to have a key defined for such dynamic Livewire component rendering, otherwise the component does not get refreshed after clicking on another tab --}}
        @livewire($viewedTab, ['model' => $model], key($viewedTab))
    </div>
</div>
