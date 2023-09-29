@extends('layouts.app')
@section('content')
    @foreach($tickets as $ticket)
        <div class="m-5 bg-amber-300">{{$ticket->description}}</div>
    @endforeach
    <div class="flex flex-row justify-between">
        {{$tickets->links()}}
    </div>
@endsection
