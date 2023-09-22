@extends('auth::layouts.auth-master')

@section('body-class', 'hold-transition login-page')
    
@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="login-brand">
                        <img src="{{ setting('colored_logo') }}" alt="logo" width="100" class="w-50-per">
                    </div>

                    <div class="card card-primary">
                        <div class="card-header"><h4>@lang('Reset Password')</h4></div>

                        <div class="card-body">
                            <p class="text-muted">@lang('We will send a link to reset your password')</p>
                            <form method="post" id="reset-password-form">
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-group mb-3">
                                    <label for="email">@lang('Email')</label>
                                    <input type="email"
                                           class="form-control" 
                                           name="email" 
                                           required="required"
                                           value="{{ $email }}" readonly="readonly">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="new-password">@lang('New Password')</label>
                                    <input type="password"
                                           class="form-control" 
                                           name="password" 
                                           required="required">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="new-password">@lang('Confirm New Password')</label>
                                    <input type="password"
                                           class="form-control" 
                                           name="password_confirmation" 
                                           required="required">
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-block">@lang('Update Password')</button>
                                    </div>
                                  <!-- /.col -->
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