@extends('app')

@section('module-name', __(strtolower(config('languages.name'))))

@section("styles")
    @parent
@stop

@section("content")

@stop

@section("scripts")
    @parent
@stop