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

                <form method="post" action="{{ route('install.install-now') }}" id="form-installation">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    @include('install::partials.wizards', [
                        'install' => 1,
                        'requirements' => 1,
                        'permissions' => 1,
                        'database' => 1,
                        'installation' => 1
                    ])
                    <div class="card card-primary">
                        <div class="card-header"><h4>{{ $pageTitle }}</h4></div>

                        <div class="card-body">
                            
                            <p>@lang('Application is ready to be installed!')</p>

                            <p>@lang('Before you can continue, kindly provide the name of your application below:')</p>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="name">@lang('App name') <span class="text-muted">(@lang('Required'))</span></label>
                                        <input type="text"
                                            name="app_name"
                                            class="form-control" 
                                            value="{{ old('app_name') }}" required>
                                    </div>
                                </div>

                            </div>

                            <button type="button" class="btn btn-primary float-right" id="start-installation">@lang('Install') <i class="fas fa-play"></i></button>
                        </div>
                    </div>
                </form>
              </div>
            </div>
        </div>
    </section>
@endsection