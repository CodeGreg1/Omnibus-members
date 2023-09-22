@extends('auth::layouts.auth-master')

@section('module-styles')
    {!! theme_style('assets/plugins/bootstrap-social/bootstrap-social.css') !!}
@endsection

@section('module-scripts')
    @if(setting('registration_captcha'))
        {!! ReCaptcha::htmlScriptTagJsApi() !!}
    @endif
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
                    <div class="card-header"><h4>@lang('Register')</h4></div>

                    <div class="card-body">
                        <form method="post"  id="register-form">
                            <div class="form-group">
                              <label for="email">@lang('Email')</label>
                              <input type="email" 
                                class="form-control" 
                                id="email" 
                                placeholder="@lang('Email')" 
                                name="email" 
                                required="required" 
                                autofocus>
                            </div>
                            <div class="form-group">
                                <label for="username">@lang('Username')</label>
                                <input type="text" 
                                    class="form-control" 
                                    id="username" 
                                    placeholder="@lang('Username')" 
                                    name="username" 
                                    required="required">
                            </div>

                            <div class="form-group">
                                <label for="password" class="d-block">@lang('Password')</label>
                                <input type="password" 
                                    class="form-control" 
                                    id="password" 
                                    placeholder="@lang('Password')" 
                                    name="password" 
                                    required="required">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="d-block">@lang('Confirm password')</label>
                                <input type="password" 
                                    class="form-control" 
                                    id="password_confirmation" 
                                    placeholder="@lang('Confirm password')" 
                                    name="password_confirmation" 
                                    required="required">
                            </div>

                            @if(setting('registration_captcha'))
                            <div class="form-group">
                                {!! htmlFormSnippet() !!}
                            </div>
                            @endif

                            @if(setting('registration_tos'))
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                  <input type="checkbox" 
                                    name="terms" 
                                    class="custom-control-input" 
                                    id="agree" 
                                    value="1"
                                    required="required">
                                  <label class="custom-control-label" for="agree">@lang('I agree with the') <a href="{{route('pages.show', setting('frontend_page_terms'))}}" target="_blank">@lang('terms and conditions')</a>.</label>
                                </div>
                            </div>
                            @endif

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    @lang('Register')
                                </button>
                            </div>
                        </form>

                        @if(setting('services_login_with_facebook') == 1 || setting('services_login_with_google') == 1)
                            @include('auth::partials.social-login')
                        @endif

                    </div>
                </div>
                <div class="mt-5 text-muted text-center">
                    <a href="{{route('auth.login.show')}}" class="text-center">@lang('I already have an account')</a>
                </div>
                @include('auth::partials.copy')
              </div>
            </div>
        </div>
    </section>
    <!-- /.register-box -->
@endsection

@section('modals')
    @include('auth::partials.terms-and-condition')
@endsection