<x-app-layout>
    <h1 class="font-medium text-4xl">IT Portal</h1>
    <p class="mt-4 pr-72">This is dedicated portal for everything IT related. You can find here guides in how to support yourself, which computer do you own, create support request, when you cannot resolve an incident event on your computer, and so forth. See for yourself.</p>
    <h2 class="mt-12 text-2xl">What do you need ?</h2>
    <div class="mt-4 flex flex-row justify-start space-x-12">
        <x-create-incident></x-create-incident>
        <x-create-request></x-create-request>
        <x-create-change></x-create-change>
    </div>
    @isset($tickets)
        <div class="mt-12 flex flex-row justify-between">
            <h2 class="text-2xl">Recent tickets you have already created: </h2>
            <a href="{{route('tickets.index')}}" class="rounded-lg shadow-md px-2 py-1 bg-white">See All</a>
        </div>
        <div class="mb-4 flex flex-col justify-start mt-4 space-y-4">
            @foreach($tickets as $ticket)
                <x-ticket-card :$ticket />
            @endforeach
        </div>
    @endif
</x-app-layout>
