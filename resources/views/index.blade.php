<x-app-layout>
    <section>
        <h1 class="mt-4">IT Portal</h1>
        <p class="text-sm mt-4 pr-72">This is a dedicated portal for everything IT related. Here you can find guides on how to support yourself, which computers are assigned to you, report IT issues, and ask for IT requests. See for yourself.</p>
    </section>
    <section>
        <h3 class="mt-12 font-medium">What do you need ?</h3>
        <div class="mt-4 flex flex-row justify-start space-x-12">
            <x-create-incident />
            <x-create-request />
        </div>
    </section>
    <section>
        <div class="w-3/4">
            @isset($incidents)
                <div class="mt-12 flex flex-row justify-between items-end">
                    <h4>Your recent incidents: </h4>
                    <a href="{{route('incidents.index')}}">
                        <x-secondary-button>See All</x-secondary-button>
                    </a>
                </div>
                <div class="my-4 flex flex-col justify-start space-y-4">
                    @foreach($incidents as $incident)
                        <x-incident-card :$incident />
                    @endforeach
                </div>
            @endif
            @isset($requests)
                <div class="mt-12 flex flex-row justify-between items-end">
                    <h4>Your recent requests: </h4>
                    <a href="{{route('requests.index')}}">
                        <x-secondary-button>See All</x-secondary-button>
                    </a>
                </div>
                <div class="mb-4 flex flex-col justify-start mt-4 space-y-4">
                    @foreach($requests as $request)
                        <x-request-card :$request />
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
