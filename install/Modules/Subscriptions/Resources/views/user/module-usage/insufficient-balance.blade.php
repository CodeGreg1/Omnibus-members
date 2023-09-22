@extends('subscriptions::layouts.error')

@section('content')
    <div class="page-error">
        <div class="container d-flex flex-column">
            <div class="row align-items-center justify-content-center min-vh-100">
                <div class="col-sm-11 col-md-9 col-lg-7 col-xl-6">
                    <div class="page-inner text-center">
                        <h1>403</h1>
                        <h4>
                            @if ($isSubscribe)
                                @lang("Insufficient module permission")
                            @else
                                @lang("Unauthorized")
                            @endif
                        </h4>
                        <div class="page-description mt-4 mb-5">
                            @if ($isSubscribe)
                                <p>@lang('You have an insufficient balance in your plan module limits.')</p>
                                <p>@lang('Kindly upgrade to get enough module limits.')</p>
                            @else
                                @lang("Kindly subscribe to get enough access to this action.")
                            @endif
                        </div>
                        <div class="buttons">
                            <a class="btn btn-info btn-lg" href="{{ route('dashboard.index') }}">
                                @lang('Back to Home')
                            </a>
                            <a href="{{ route('user.subscriptions.pricings.index') }}" class="btn btn-primary btn-lg btn-icon icon-left">
                                @if ($isSubscribe)
                                    @lang("Upgrade Now")
                                @else
                                    @lang("Subscribe Now")
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
