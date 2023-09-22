@extends('app')

@section('module-name', __(strtolower(config('tickets.name'))))

@section("styles")
    @parent
@stop

@section("content")

@stop

@section("scripts")
    @if(file_exists(public_path() . '/js/tmp/modules/tickets.js') && !file_exists(public_path() . '/js/tmp/adminTickets.js'))
        <script src="{{ url('js/tmp/modules/tickets.js') }}"></script>
    @endif

    @parent
@stop