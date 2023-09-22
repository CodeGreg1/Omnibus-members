@extends('install::layouts.master')

@section('module-styles')

@endsection

@section('module-scripts')

@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2 installation-section">
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

                    @include('install::partials.wizards', [
                        'install' => 1,
                        'requirements' => 1,
                        'permissions' => 1,
                        'database' => 1,
                        'installation' => 1,
                        'completed' => 1,
                        'error' => 1
                    ])



                    <form method="post" action="{{ route('install.install-now') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="card card-primary">
                            <div class="card-header"><h4>{{ $pageTitle }}</h4></div>

                            <div class="card-body">

                                <p><b>@lang('Something went wrong during the installation!')</b></p>

                                <p class="alert alert-warning">Please check the log inside <code>storage/logs</code> to see what's wrong.</p>

                                <a class="btn btn-primary float-right" href="{{ route('install.index') }}">
                                    <i class="fas fa-undo"></i>
                                    @lang('Retry')
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