@extends('app')

@section('module-name', __(strtolower(config('categories.name'))))

@section("styles")
    @parent
@stop

@section("content")

@stop

@section("scripts")
    @if(file_exists(public_path() . '/js/tmp/modules/categories.js') && !file_exists(public_path() . '/js/tmp/adminCategories.js'))
        <script src="{{ url('js/tmp/modules/categories.js') }}"></script>
    @endif

    @parent
@stop