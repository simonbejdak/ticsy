<x-app-layout>
    <h1>IT Portal</h1>
    <p class="mt-4 pr-72">This is dedicated portal for everything IT related. You can find here guides in how to support yourself, which computer do you own, create support request, when you cannot resolve an incident event on your computer, and so forth. See for yourself.</p>
    <h3 class="mt-12">What do you need ?</h3>
    <div class="mt-2 flex flex-row justify-start space-x-12">
        <x-create-incident></x-create-incident>
        <x-create-request></x-create-request>
        <x-create-change></x-create-change>
    </div>
    @isset($tickets)
        <div class="mt-12 flex flex-row justify-between">
            <h3>Recent tickets you have already created: </h3>
            <a href="{{route('tickets.index')}}">
                <x-secondary-button>See All</x-secondary-button>
            </a>
        </div>
        <div class="mb-4 flex flex-col justify-start mt-4 space-y-4">
            @foreach($tickets as $ticket)
                <x-ticket-card :$ticket />
            @endforeach
        </div>
    @endif
</x-app-layout>
