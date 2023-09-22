@extends('app')

@section('module-name', __(strtolower(config('categorytypes.name'))))

@section("styles")
    @parent
@stop

@section("content")

@stop

@section("scripts")
    @if(file_exists(public_path() . '/js/tmp/modules/category_types.js') && !file_exists(public_path() . '/js/tmp/adminCategoryTypes.js'))
        <script src="{{ url('js/tmp/modules/category_types.js') }}"></script>
    @endif

    @parent
@stop