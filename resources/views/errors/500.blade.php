@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __("We're sorry, the page you requested is experiencing technical difficulties."))
