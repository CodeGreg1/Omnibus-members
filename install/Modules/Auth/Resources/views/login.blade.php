@extends('auth::layouts.auth-master')

@section('module-styles')
    {!! theme_style('assets/plugins/bootstrap-social/bootstrap-social.css') !!}
@endsection

@section('theme_script_plugin')
    
@endsection

@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="login-brand">
                        <img src="{{ setting('colored_logo') }}" alt="logo" width="100" class="w-50-per">
                    </div>

                    <div class="card card-primary">
                        <div class="card-header"><h4>@lang('Login')</h4></div>

                        <div class="card-body">
                            <form method="post" id="login-form">

                                @include('partials.messages')

                                <div class="form-group">
                                    <label for="username">@lang('Email or Username')</label>
                                   <input type="text" 
                                        class="form-control" 
                                        placeholder="@lang('Email or Username')" 
                                        name="username" 
                                        required="required"
                                        id="username">
                                </div>

                                <div class="form-group">
                                    <div class="d-block">
                                        <label for="password" class="control-label">@lang('Password')</label>
                                        @if(setting('forgot_password'))
                                        <div class="float-right">
                                            <a href="{{route('password.request')}}" class="text-small">
                                                @lang('Forgot Password?')
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    <input type="password" 
                                        class="form-control" 
                                        placeholder="@lang('Password')" 
                                        name="password" 
                                        required="required"
                                        id="password">
                                </div>

                                @if(setting('remember_me'))
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                            name="remember" 
                                            class="custom-control-input" 
                                            tabindex="3" 
                                            id="remember-me" 
                                            value="1">
                                        <label class="custom-control-label" for="remember-me">@lang('Remember Me')</label>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group mb-0">
                                    <button type="submit" 
                                        class="btn btn-primary btn-lg btn-block">
                                        @lang('Login')
                                    </button>
                                </div>
                            </form>

                            @if(setting('services_login_with_facebook') == 1 
                            || setting('services_login_with_google') == 1)
                                @include('auth::partials.social-login')
                            @endif

                        </div>
                    </div>

                    @if(setting('allow_registration'))
                        <div class="mt-5 text-muted text-center">
                            @lang("Don't have an account?") <a href="{{ route('auth.register.show') }}">@lang('Create One')</a>
                        </div>
                    @endif
                    @include('auth::partials.copy')
                </div>
            </div>
        </div>
    </section>
@endsection