@extends('app')

@section('module-name', __(strtolower(config('orders.name'))))

@section("styles")
    @parent
@stop

@section("content")

@stop

@section("scripts")
    @parent
@stop