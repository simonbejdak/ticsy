<x-app-layout>
    @foreach($tickets as $ticket)
        <x-ticket-card :$ticket />
    @endforeach
    {{$tickets->links()}}
</x-app-layout>
