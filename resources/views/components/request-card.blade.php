<a href="{{route('requests.edit', $request)}}">
    <x-card>
        <div class="flex flex-col rounded-sm shadow-md px-4 py-2 bg-white">
            <h2 class="font-bold text-lg">
                <span class="hover:underline">{{ucfirst($request->category->name)}}</span>
            </h2>
            <p>{{$request->description}}</p>
            <hr class="my-4 border-gray-300">
            <div class="flex flex-row justify-between">
                <div class="text-xs">Created {{$request->created_at->diffForHumans()}} by {{$request->caller->name}}</div>
                @if($request->resolver)
                    <div class="text-xs">Assigned to: {{$request->resolver->name}}</div>
                @endif
            </div>
        </div>
    </x-card>
</a>
