<x-app-layout>
    <h1 class="text-2xl font-bold">{{ucfirst($ticket->category->name)}}</h1>
    <p class="mt-4">{{$ticket->description}}</p>
    <form action="{{ route('tickets.update', $ticket) }}" method="POST">
        @method('PATCH')
        @csrf
        <hr class="border border-gray-400 mt-8">
        <div class="mt-8">
            <div class="flex flex-col space-y-8">
                <div class="flex flex-col space-y-2">
                    <label class="font-bold text-lg" for="priority">Priority</label>
                    <select class="rounded-lg text-center shadow-md py-2" name="priority" id="priority">
                        @foreach($priorities as $priority)
                            @empty(!old('$priority'))
                                <option {{ ($priority == old('priority')) ? 'selected' : '' }} value="{{ $priority }}">{{$priority}}</option>
                            @else
                                <option {{ $priority == $ticket->priority ? 'selected' : '' }} value="{{ $priority }}">{{$priority}}</option>
                            @endempty
                        @endforeach
                    </select>
                    @error('priority')
                        <div class="text-red-500 text-md">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex flex-col space-y-2 mt-8">
                <button class="rounded-lg py-2 bg-slate-800 text-white hover:bg-slate-600 shadow-md" type="submit">Save</button>
            </div>
        </div>
    </form>
</x-app-layout>
