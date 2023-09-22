@extends('subscriptions::layouts.master')

@section('content')
    <div class=row>
        <div class="col-12 mb-4">
            <div class="hero bg-success text-white">
                <div class="hero-inner">
                    <h2>@lang('Success')!</h2>
                    <p class="lead">
                        @lang('You have successfully subscribed :package plan. Thank you for choosing :app_name. You can now check your account.', [
                            'package' => $package ? $package->name : '',
                            'app_name' => setting('app_name')
                        ])
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
