<div class="flex flex-col">
    <form wire:submit.prevent="addComment">
        <div class="flex flex-row my-2 space-x-2">
            <label hidden for="body">Comment</label>
            <input wire:model="body" placeholder="Add a comment" class="w-full p-2 rounded-md border border-gray-300">
            <x-secondary-button>Add</x-secondary-button>
        </div>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
    </form>
    @if(count($activities))
        <div class="flex flex-col mt-4 mb-10 space-y-2">
            @foreach($activities as $activity)
                <x-activity-card :activity="$activity" />
            @endforeach
        </div>
    @endif
</div>
