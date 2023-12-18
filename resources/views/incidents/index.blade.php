<x-app-layout>
    <h1 class="font-medium text-4xl">Your Incidents</h1>
    <div class="mt-8 flex flex-col space-y-4">
        @foreach($incidents as $incident)
            <x-incident-card :$incident />
        @endforeach
        {{ $incidents->links() }}
    </div>
</x-app-layout>
