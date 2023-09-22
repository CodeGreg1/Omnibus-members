@extends('auth::layouts.auth-master')
    
@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="login-brand">
                        <img src="{{ setting('colored_logo') }}" alt="logo" width="100" class="w-50-per">
                    </div>

                    <div class="card card-primary">
                        <div class="card-header"><h4>@lang('Two Factor Authentication')</h4></div>

                        <div class="card-body">
                            <form method="post" action="{{ route('auth.two-factor.verify') }}" id="2fa-login-verify-form">
                                @include('partials.messages')
                                @csrf
                                <div class="form-group">
                                    <input type="number" class="form-control" placeholder="@lang('Authy Token')" name="authy_token" required="required" autofocus>
                                </div>

                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        @lang('Verify')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @include('auth::partials.copy')
                </div>
            </div>
        </div>
    </section>
@endsection