<div class="flex flex-col">
    @if(count($tasks))
        <table class="my-2">
            <tr class="border border-separate border-gray-300">
                <th class="text-left pl-3 py-2">Number</th>
                <th class="text-left">Description</th>
                <th class="text-right pr-4">Status</th>
            </tr>
            @foreach($tasks as $task)
                <tr class="border border-gray-300 hover:bg-gray-200 hover:cursor-pointer ease-in transition duration-70" >
                    <td class="text-left pl-3 py-2">
                        <a href="{{ route('tasks.edit', $task) }}">
                            {{ $task->number }}
                        </a>
                    </td>
                    <td class="text-left">
                        <a href="{{ route('tasks.edit', $task) }}">
                            {{ $task->description }}
                        </a>
                    </td>
                    <td class="text-right pr-4">
                        <a href="{{ route('tasks.edit', $task) }}">
                            {{ $task->status->name }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
</div>
