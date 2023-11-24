<div class="flex flex-row font-light">
    <div class="border-l rounded-l-md border-2 border-black {{ $style }}"></div>
    <div class="flex flex-col bg-white w-full px-4 py-2 rounded-r-md rounded-y-md border border-gray-300">
        <div class="flex flex-row items-center justify-between">
            <x-simple-profile-card :user="$activity->causer" />
            <div class="text-xs">{{ $activity->created_at->format('d.m.Y h:m') }}</div>
        </div>
        <div class="mt-4 text-xs flex flex-col">
            @if(is_array($body))
                @foreach($body as $bodyRow)
                    <p>{{ $bodyRow }}</p>
                @endforeach
            @else
                <p>{{ $body }}</p>
            @endif
        </div>
    </div>
</div>
