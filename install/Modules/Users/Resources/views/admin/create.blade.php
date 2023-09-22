@extends('users::layouts.master')

@section('module-styles')

@endsection

@section('module-scripts')

@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.users.index') }}"> {!! config('users.name') !!}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{$pageTitle}}</a>
        </li>
    </ol>
@endsection

@section('content')
    
    <div class="row">
        <div class="col-md-8">

            <div class="card card-form" id="details">
                <div class="card-header">
                    <h4>{{ $pageTitle }}</h4>
                </div>

                <form id="user-create-form" method="post" data-action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="email">@lang('Email') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input name="email" 
                                        class="form-control" 
                                        type="text">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="first_name">@lang('First name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input name="first_name" 
                                        class="form-control" 
                                        type="text">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="last_name">@lang('Last name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input name="last_name" 
                                        class="form-control" 
                                        type="text">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="role">@lang('User role') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="role" 
                                        class="form-control select2">
                                        <option value="">@lang('Select role')</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="password">@lang('Password')</label>
                                    <input name="password" 
                                        placeholder="@lang('Leave blank we will send password via invitation')" 
                                        class="form-control" 
                                        type="password">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password">@lang('Confirm new password')</label>
                                    <input name="confirm_password" 
                                        placeholder="@lang('Leave blank we will send password via invitation')"
                                        class="form-control" 
                                        type="password">
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="send_invitation" value="1" checked>
                                        <label class="form-check-label" for="send_invitation">@lang('Send invitation')</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="alert alert-info">
                                    <strong>@lang('NOTE'):</strong> @lang('We will still send email invitation if password don\'t have a value.')
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button type="submit" class="btn btn-primary btn-lg float-right">@lang('Save changes')</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>            
            </div>

        </div> 

    </div>

@endsection
