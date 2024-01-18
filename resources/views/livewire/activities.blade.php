<div class="flex flex-col">
    @if(count($activities))
        <div class="flex flex-col mt-4 mb-10 space-y-2">
            @foreach($activities as $activity)
                <x-activity-card :activity="$activity" />
            @endforeach
        </div>
    @endif
</div>
