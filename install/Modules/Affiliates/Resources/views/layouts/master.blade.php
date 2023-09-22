@extends('app')

@section('module-name', __(strtolower(config('affiliates.name'))))

@section("styles")
    @parent
@stop

@section("content")

@stop

@section("scripts")
    @if(file_exists(public_path() . '/js/tmp/modules/affiliates.js') && !file_exists(public_path() . '/js/tmp/adminAffiliates.js'))
        <script src="{{ url('js/tmp/modules/affiliates.js') }}"></script>
    @endif

    @parent
@stop