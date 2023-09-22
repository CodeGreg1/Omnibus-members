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
                        <div class="card-header"><h4>@lang('Enter recovery code')</h4></div>

                        <div class="card-body">
                            <p class="text-muted">@lang('To recover your account you must enter your recovery code.')</p>
                            <form method="post" id="recovery-form">
                                @include('partials.messages')
                                @csrf
                                <div class="form-group">
                                    <label for="one_time_password">@lang('Recovery Code')</label>
                                    <input type="text" class="form-control" placeholder="@lang('Recovery Code')" name="recovery_code" required="required" autofocus>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        @lang('Verify')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mt-5 text-muted text-center">
                        <h6>@lang('You have your phone?')</h6>
                        <a href="{{route('auth.login.show')}}" class="text-center">@lang('Enter authentication code')</a>
                    </div>
                    @include('auth::partials.social-login')
                </div>
            </div>
        </div>
    </section>
@endsection