@extends('app')

@section('module-name', __(strtolower(config('services.name'))))

@section("styles")
    @parent
@stop

@section("content")

@stop

@section("scripts")
    @if(file_exists(public_path() . '/js/tmp/modules/services.js') && !file_exists(public_path() . '/js/tmp/adminServices.js'))
        <script src="{{ url('js/tmp/modules/services.js') }}"></script>
    @endif

    @parent
@stop