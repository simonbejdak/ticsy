<a href="{{route('tickets.edit', $ticket)}}">
    <x-card>
        <div class="flex flex-col rounded-sm shadow-md px-4 py-2 bg-white">
            <h2 class="font-bold text-lg">
                <span class="hover:underline">{{ucfirst($ticket->category->name)}}</span>
            </h2>
            <p>{{$ticket->description}}</p>
            <hr class="my-4 border-gray-300">
            <div class="flex flex-row justify-between">
                <div class="text-xs">Created {{$ticket->created_at->diffForHumans()}} by {{$ticket->user->name}}</div>
                @if($ticket->resolver)
                    <div class="text-xs">Assigned to: {{$ticket->resolver->name}}</div>
                @endif
            </div>
        </div>
    </x-card>
</a>
