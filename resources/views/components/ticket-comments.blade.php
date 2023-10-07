@props(['comments', 'ticket'])
<div class="flex flex-col">
    <hr class="my-4 border-gray-300">
    <div>
        <form action="{{route('tickets.add-comment', $ticket)}}" method="POST">
            @method('PATCH')
            @csrf
            <div class="flex flex-row mt-2 space-x-2">
                <label hidden for="body">Comment</label>
                <input placeholder="Add a comment" name="body" id="body" class="w-full p-2 rounded-md border border-gray-300" type="text">
                <x-secondary-button>Add</x-secondary-button>
            </div>
            <x-input-error :messages="$errors->get('body')" class="mt-2" />
        </form>
        @if(count($comments))
            <div class="flex flex-col mt-4 space-y-2">
                @foreach($comments as $comment)
                    <div class="flex flex-row font-light">
                        <div class="border-l rounded-l-md border-2 border-black"></div>
                        <div class="flex flex-col bg-white w-full px-4 py-2 rounded-r-md rounded-y-md border border-gray-300">
                            <p>{{$comment->body }}</p>
                            <div class="mt-4 text-xs">{{$comment->user->name . ' at ' . $comment->created_at->format('h:m, m.d.Y')}}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
