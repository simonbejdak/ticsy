<a href="{{route('incidents.edit', $incident)}}">
    <x-card>
        <div class="flex flex-col rounded-sm shadow-md px-4 py-2 bg-white">
            <h2 class="font-bold text-lg">
                <span class="hover:underline">{{ucfirst($incident->category->name)}}</span>
            </h2>
            <p>{{$incident->description}}</p>
            <hr class="my-4 border-gray-300">
            <div class="flex flex-row justify-between">
                <div class="text-xs">Created {{$incident->created_at->diffForHumans()}} by {{$incident->caller->name}}</div>
                @if($incident->resolver)
                    <div class="text-xs">Assigned to: {{$incident->resolver->name}}</div>
                @endif
            </div>
        </div>
    </x-card>
</a>
