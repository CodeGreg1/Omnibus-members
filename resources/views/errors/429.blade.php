@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('You have exceeded the maximum number of requests allowed.'))
