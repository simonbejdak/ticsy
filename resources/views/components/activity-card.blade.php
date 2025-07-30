 <div class="flex flex-row justify-end col-span-2">
    <div class="border-l rounded-l-sm border-3 border-black {{ $style }}"></div>
    <div class="flex flex-col bg-white w-full px-4 py-2 rounded-r-sm rounded-y-sm border-y border-r border-slate-400">
        <div class="flex flex-row items-center justify-between">
            <x-simple-profile-card :user="$activity->causer" />
            <div class="text-xxs">{{ ucfirst(str_replace('_', ' ', $activity->event)) }} &bullet; {{ $activity->created_at->format('d.m.Y h:m') }}</div>
        </div>
        <div class="mt-4 text-xs flex flex-col">
            {!! $body !!}
        </div>
    </div>
</div>
