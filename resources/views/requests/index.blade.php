<x-app-layout>
    <h1 class="font-medium text-4xl">Your Requests</h1>
    <div class="mt-8 flex flex-col space-y-4">
        @foreach($requests as $request)
            <x-request-card :$request />
        @endforeach
        {{ $requests->links() }}
    </div>
</x-app-layout>
