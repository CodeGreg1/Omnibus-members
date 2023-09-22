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

                <form method="post" action="{{ route('install.start') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    @include('install::partials.wizards', [
                        'install' => 1,
                        'requirements' => 1,
                        'permissions' => 1,
                        'database' => 1
                    ])
                    <div class="card card-primary">
                        <div class="card-header"><h4>{{ $pageTitle }}</h4></div>

                        <div class="card-body">
                            
                            @include('partials.messages')
                                
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="name">@lang('Host') <span class="text-muted">(@lang('Required'))</span></label>
                                        <input type="text"
                                            name="host"
                                            class="form-control" 
                                            value="{{ old('host') }}" required>
                                        <small class='form-text text-muted form-help'>@lang('Database host. In default you can enter localhost or mysql.')</small>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="name">@lang('Username') <span class="text-muted">(@lang('Required'))</span></label>
                                        <input type="text"
                                            name="username"
                                            class="form-control" 
                                            value="{{ old('username') }}" required>
                                        <small class='form-text text-muted form-help'>@lang('Database username.')</small>
                                    </div>
                                </div>

                                @if(is_localhost())
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="name">@lang('Password') <span class="text-muted">(@lang('Optional'))</span></label>
                                        <input type="password"
                                            name="password"
                                            class="form-control" 
                                            value="{{ old('password') }}">
                                        <small class='form-text text-muted form-help'>@lang('Database password.')</small>
                                    </div>
                                </div>
                                @else
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="name">@lang('Password') <span class="text-muted">(@lang('Required'))</span></label>
                                        <input type="password"
                                            name="password"
                                            class="form-control" 
                                            value="{{ old('password') }}" required>
                                        <small class='form-text text-muted form-help'>@lang('Database password.')</small>
                                    </div>
                                </div>
                                @endif

                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="name">@lang('Database name') <span class="text-muted">(@lang('Required'))</span></label>
                                        <input type="text"
                                            name="database"
                                            class="form-control" 
                                            value="{{ old('database') }}" required>
                                        <small class='form-text text-muted form-help'>@lang('Database name where the tables created.')</small>
                                    </div>
                                </div>



                            </div>

                            <button type="submit" class="btn btn-primary float-right">@lang('Next') <i class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
              </div>
            </div>
        </div>
    </section>
@endsection