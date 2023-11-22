<div class="flex flex-row font-light">
    <div class="border-l rounded-l-md border-2 border-black {{ $activity['border_color'] }}"></div>
    <div class="flex flex-col bg-white w-full px-4 py-2 rounded-r-md rounded-y-md border border-gray-300">
        <div class="flex flex-row items-center justify-between">
            <x-simple-profile-card :user="$activity['user']" />
            <div class="text-xs">{{ $activity['created_at']->format('d.m.Y h:m') }}</div>
        </div>
        @if($activity['event'] === 'comment')
            <p class="mt-2">{{ $activity['body'] }}</p>
        @elseif($activity['event'] === 'created')
            <div class="mt-4 text-xs flex flex-col">
                <p>Ticket was created at {{ $activity['created_at'] }}</p>
            </div>
        @else
            <div class="mt-4 text-xs flex flex-col">
                @foreach($activity['rows'] as $row)
                    <p>
                        {{ $row['field_name'] . ': ' .
                           (($row['new_value'] != '') ? $row['new_value'] : 'empty') . ' was ' .
                           (($row['old_value'] != '') ? $row['old_value'] : 'empty') }}
                    </p>
                @endforeach
            </div>
        @endif
    </div>
</div>
