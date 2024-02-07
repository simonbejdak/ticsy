<a href="{{route('requests.edit', $request)}}">
    <x-card>
        <div class="flex flex-col justify-between rounded-sm shadow-md px-4 pt-2 pb-1">
            <p class="text-xs">{{$request->description}}</p>
            <div class="flex flex-row mt-4 justify-between text-xxs">
                <div>Status: {{ $request->status->value }}</div>
                @if($request->resolver)
                    <div>Assigned to: {{$request->resolver->name}}</div>
                @endif
            </div>
        </div>
    </x-card>
</a>
