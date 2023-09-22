@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')

@section('message')
    @if (setting('maintenance_reason'))
        {!! setting('maintenance_reason') !!}
    @else
        @lang('Our servers are currently undergoing maintenance, and the service is temporarily unavailable.')
    @endif
@endsection
