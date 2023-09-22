@extends('carts::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {!! $pageTitle!!}</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')

    <div id="user-cart-datatable"></div>
    @auth
        <div class="d-flex align-items-start mt-2">
            <a href="{{ route('user.carts.checkout.index') }}" data-href="{{ route('user.carts.checkout.index') }}" class="btn btn-primary btn-lg float-right disabled" id="btn-cart-checkout">@lang('Proceed to checkout')</a>
        </div>
    @else
    <div class="d-flex alitn-items-center justify-content-between">
        <a href="/">@lang('Return to shop')</a>
        <a href="/user/products" class="btn btn-primary">@lang('Continue to shipping')</a>
    </div>
    @endauth

@endsection

@section('modals')
    @if (session()->has('pruchase'))
        @include('carts::modals.checkout-success')
    @endif
@endsection
