<div class="flex flex-col mb-4">
    <div class="flex flex-row">
        <div
            wire:click="setViewedTab('activities')"
            class="{{ $viewedTab === 'activities' ? 'bg-white border-t border-x border-gray-300 rounded-t-lg' : '' }} z-10 px-3 pt-1 pb-2 hover:cursor-pointer"
        >
            Activities
        </div>
        <div
            wire:click="setViewedTab('tasks')"
            class="{{ $viewedTab === 'tasks' ? 'bg-white border-t border-x border-gray-300 rounded-t-lg' : '' }} z-10 px-3 pt-1 pb-2  hover:cursor-pointer"
        >Tasks</div>
    </div>
        <div class="bg-white border border-gray-300 -mt-1 p-4">
        @if($viewedTab === 'activities')
            <livewire:activities :model="$request" />
        @elseif($viewedTab === 'tasks')
            <livewire:tasks :model="$request" />
        @endif
    </div>
</div>
