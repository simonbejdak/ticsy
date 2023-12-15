<x-app-layout>
    <h1 class="font-medium text-4xl">Your Tickets</h1>
    <div class="mt-8 flex flex-col space-y-4">
        @foreach($tickets as $ticket)
            <x-ticket-card :$ticket />
        @endforeach
        {{ $tickets->links() }}
    </div>
</x-app-layout>
