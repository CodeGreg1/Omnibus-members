@extends('auth')

@section('module-name', __(strtolower(config('subscriptions.name'))))

@section("styles")
    @parent
@stop

@section("content")

@stop

@section("scripts")
    @parent
@stop
