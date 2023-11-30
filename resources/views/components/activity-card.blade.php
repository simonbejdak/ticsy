<div class="flex flex-row">
    <div class="border-l rounded-l-md border-3 border-black {{ $style }}"></div>
    <div class="flex flex-col bg-white w-full px-4 py-2 rounded-r-md rounded-y-md border-y border-r border-gray-300">
        <div class="flex flex-row items-center justify-between">
            <x-simple-profile-card :user="$activity->causer" />
            <div class="text-xxs">{{ ucfirst(str_replace('_', ' ', $activity->event)) }} &bullet; {{ $activity->created_at->format('d.m.Y h:m') }}</div>
        </div>
        <div class="mt-4 text-xs flex flex-col">
            @if($activity->event === 'comment' || $activity->event === 'priority_change_reason')
                <p>{{ $activity->description }}</p>
            @else
                <table class="border-separate border-spacing-x-2 w-1/2">
                    @foreach($activity->changes['attributes'] as $field => $value)
                        <tr class="border-spacing-y-3">
                            <td class="text-right w-1/6">
                                {{ ucfirst(
                                    strtolower(
                                        preg_replace('/(?<!\ )[A-Z]/', ' $0',
                                            str_replace('.name', '', $field)
                                                )
                                            )
                                    )
                                }}:
                            </td>
                            <td class="text-left">
                                @if($activity->event === 'updated')
                                    {{ $value ?? 'empty' }} was {{ $activity->changes['old'][$field] ?? 'empty' }}
                                @else
                                    {{ $value ?? '' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
{{--            @if(is_array($body))--}}
{{--                @foreach($body as $bodyRow)--}}
{{--                    <p>{{ $bodyRow }}</p>--}}
{{--                @endforeach--}}
{{--            @else--}}
{{--                <p>{{ $body }}</p>--}}
{{--            @endif--}}
        </div>
    </div>
</div>
