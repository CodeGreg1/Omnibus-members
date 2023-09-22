@extends('install::layouts.master')

@section('module-styles')

@endsection

@section('module-scripts')

@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    <section class="section installation-section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2">
                <div class="login-brand">
                    <img src="{{ setting('colored_logo') }}" alt="logo" width="100" class="shadow-light">
                </div>

                <form method="post" action="{{ route('install.install-now') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    @include('install::partials.wizards', [
                        'install' => 1,
                        'requirements' => 1,
                        'permissions' => 1,
                        'database' => 1,
                        'installation' => 1,
                        'completed' => 1
                    ])
                    <div class="card card-primary">
                        <div class="card-header"><h4>{{ $pageTitle }} <i class="fas fa-check text-success"></i> </h4></div>

                        <div class="card-body">
                            
                            <p><strong>@lang('Well Done!')</strong></p>

                            <p>@lang('The application is now successfully installed! You can login by clicking on "Login" button below.')</p>

                            @if (is_writable(base_path()))
                                <p class="alert alert-warning"><strong>Important!</strong> Since your root directory is still writable, be sure to change the permissions to 755 so that only the root user has the writable access.</p>
                            @endif

                            <a class="btn btn-primary float-right" href="{{ route('auth.login.show') }}">
                                <i class="fa fa-sign-in"></i>
                                @lang('Login')
                            </a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </form>
              </div>
            </div>
        </div>
    </section>
@endsection