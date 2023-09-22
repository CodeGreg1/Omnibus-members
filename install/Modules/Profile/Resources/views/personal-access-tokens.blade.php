@extends('users::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('policies')
    app.policies = {!! json_encode($policies) !!};
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)">{{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#add-new-personal-token-modal" data-backdrop="static" data-keyboard="false">@lang('Add new token')</button>
@endsection

@section('content')
    @include('partials.messages')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>@lang('Personal Access Tokens')</h4>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive table-invoice">
                    <table class="table table-striped">
                        @if(count($tokens))
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th width="18%">@lang('Last Used')</th>
                                    <th width="18%">@lang('Created On')</th>
                                    <th width="12%">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tokens as $token)
                                    <tr>
                                        <td>{{ $token->name }}</td>
                                        <td>{{ user_date_format($token->last_used_at) }}</td>
                                        <td>{{ user_date_format($token->created_at) }}</td>
                                        <td><button class="btn btn-danger btn-revoke-personal-access-token" data-id="{{ $token->id }}"><i class="fa fa-trash"></i> Revoke</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <div class="text-center">@lang('No records found!')</div>
                                    </tr>
                                </tbody>
                            </table>
                            
                        @endif
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('profile::partials.modals.add-new-personal-access-token')
@endsection
