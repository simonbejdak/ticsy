<a href="{{route('incidents.edit', $incident)}}">
    <x-card>
        <div class="flex flex-col justify-between rounded-sm shadow-md px-4 pt-2 pb-1">
            <p class="text-xs line-clamp-2">{{$incident->description}}</p>
            <div class="flex flex-row mt-4 justify-between text-xxs">
                <div>Status: {{ $incident->status->value }}</div>
                @if($incident->resolver)
                    <div>Assigned to: {{$incident->resolver->name}}</div>
                @endif
            </div>
        </div>
    </x-card>
</a>
