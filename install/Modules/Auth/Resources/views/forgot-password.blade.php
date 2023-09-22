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
                        <div class="card-header"><h4>@lang('Forgot your password?')</h4></div>

                        <div class="card-body">
                        <p class="text-muted">@lang('Please provide your email below and we will send you a password reset link.')</p>
                        <form method="post" id="forgot-password-form">
                            <div class="form-group">
                                <label for="email">@lang('Email')</label>
                                <input type="email" id="email" class="form-control" placeholder="@lang('Email')" name="email" required="required" autofocus>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    @lang('Request new password')
                                </button>
                            </div>
                        </form>
                        </div>
                    </div>

                    <div class="mt-5 text-muted text-center">
                        <a href="{{route('auth.login.show')}}" class="text-center">@lang('Login account')</a>
                    </div>
                    @include('auth::partials.copy')
                </div>
            </div>
        </div>
    </section>
@endsection