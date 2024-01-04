<div class="flex flex-col">
    @if(count($tasks))
        <table class="my-2">
            <tr class="border border-separate border-gray-300">
                <th class="text-left pl-3 py-2">Number</th>
                <th class="text-left">Description</th>
                <th class="text-right pr-4">Status</th>
            </tr>
            @foreach($tasks as $task)
                <tr class="border border-gray-300" >
                    <td class="text-left pl-3 py-2"><a href="{{ route('tasks.edit', $task) }}">{{ $task->number }}</a></td>
                    <td class="text-left">{{ $task->description }}</td>
                    <td class="text-right pr-4">{{ $task->status->name }}</td>
                </tr>
            @endforeach
        </table>
    @endif
</div>
