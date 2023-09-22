@extends('subscriptions::layouts.master')

@section('content')
    <div class="row">
        <div class="col-12 mb-4">
            <div class="hero bg-warning text-white">
                <div class="hero-inner">
                    <h2>Opps...</h2>
                    <p class="lead">@lang('Subscription checkout process is cancelled.')</p>
                    <div class="mt-4">
                        <a href="{{ route('user.subscriptions.pricings.index') }}" class="btn btn-outline-white btn-lg btn-icon icon-left">@lang('Go back to pricing table')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
