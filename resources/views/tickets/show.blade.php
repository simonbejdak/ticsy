<x-app-layout>
    <h1 class="text-2xl font-bold">{{ucfirst($ticket->category->name)}}</h1>
    <p class="mt-4">{{$ticket->description}}</p>
    <div class="mt-8 flex flex-row justify-between">
        <a class="rounded-lg shadow-md px-2 py-1 bg-white" href="{{route('tickets.edit', $ticket)}}">Edit</a>
        <form action="{{route('tickets.destroy', $ticket)}}" method="POST">
            @method('DELETE')
            @csrf
            <button type="submit" class="rounded-lg shadow-md px-2 py-1 bg-red-500 text-white" href="{{route('tickets.destroy', $ticket)}}">Delete</button>
        </form>
    </div>
</x-app-layout>
