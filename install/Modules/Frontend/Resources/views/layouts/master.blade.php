@extends('app')

@section('module-name', __(strtolower(config('frontend.name'))))

@section("styles")
    @parent
@stop

@section("content")

@stop

@section("scripts")
    @if(file_exists(public_path() . '/js/tmp/modules/frontend.js') && !file_exists(public_path() . '/js/tmp/adminFrontend.js'))
        <script src="{{ url('js/tmp/modules/frontend.js') }}"></script>
    @endif

    @parent
@stop