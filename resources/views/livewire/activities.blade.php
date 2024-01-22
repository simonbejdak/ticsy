<div class="flex flex-col">
    @if(count($activities))
        <div class="flex flex-col space-y-2">
            @foreach($activities as $activity)
                <x-activity-card :activity="$activity" />
            @endforeach
        </div>
    @endif
</div>
