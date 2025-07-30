<div class="flex flex-col">
    <div class="flex flex-row">
        @foreach($this->tabs() as $tab)
            <div
                wire:click="setViewedTab('{{ $tab }}')"
                class="{{ $this->viewedTab() === $tab ? 'bg-white border-t border-x border-slate-400 text-xs rounded-t-sm' : '' }} z-10 px-3 pt-1 pb-2 hover:cursor-pointer"
            >{{ ucfirst($tab->value) }}</div>
        @endforeach
    </div>
    <div class="bg-white border border-slate-400 -mt-1 px-4 py-8 mb-4">
{{--        Due to Livewire nature we need to have a key defined for such dynamic Livewire component rendering, otherwise the component does not create refreshed properly after clicking on another tab --}}
        @livewire($this->viewedTab()->value, ['model' => $model], key(array_search($this->viewedTab(), $this->tabs())))
    </div>
</div>
