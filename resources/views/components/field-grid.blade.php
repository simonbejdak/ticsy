@props(['fields'])
@isset($fields)
    <div class="pl-40 grid grid-cols-2 grid-rows-{{ round(count($fields) / 2) }} grid-flow-col gap-y-1.5 gap-x-80 w-full'" >
        @foreach($fields as $field)
            <x-field :$field />
        @endforeach
    </div>
@else
    <div class="pl-40 grid grid-cols-2 gap-y-1.5 gap-x-80 w-full'" >
        {{ $slot }}
    </div>
@endisset
