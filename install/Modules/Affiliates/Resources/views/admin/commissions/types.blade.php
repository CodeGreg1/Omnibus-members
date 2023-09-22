@extends('affiliates::layouts.master')

@section('module-scripts')
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
            <a href="javascript:void(0)"> @lang('Commission types')</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    <div class="card">
        <div class="card-header">
            <h4>@lang('Commission types')</h4>
        </div>
            <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">@lang('Name')</th>
                        <th scope="col">@lang('Levels')</th>
                        <th scope="col" class="hidden md:table-cell">@lang('Status')</th>
                        <th scope="col" style="width: 100px;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commission_types as $alias => $type)
                        <tr data-json="{{ json_encode($type) }}">
                            <th scope="row">
                                {{ $type['name'] }}
                            </th>
                            <td>{{ count($type['levels']) }}</td>
                            <td class="hidden md:table-cell">
                                @if ($type['active'])
                                    <span class="badge badge-primary">@lang('Enabled')</span>
                                @else
                                    <span class="badge badge-danger">@lang('Disabled')</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="admin-affiliate-settings-action-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" data-button="#admin-affiliate-settings-action-dropdown">
                                    <a class="dropdown-item btn-admin-affiliate-setting-view" role="button" href="javascript:void(0)">
                                        @lang('View settings')
                                    </a>
                                    <a class="dropdown-item btn-admin-affiliate-setting-edit" role="button" href="javascript:void(0)">
                                        @lang('Edit settings')
                                    </a>
                                    @if ($type['active'])
                                        <a class="dropdown-item btn-admin-affiliate-setting-disable" role="button" href="javascript:void(0)" data-name="{{ $type['name'] }}" data-route="{{ route('admin.affiliates.commission-types.disable', $type['id']) }}">
                                            @lang('Disable')
                                        </a>
                                    @else
                                        <a class="dropdown-item btn-admin-affiliate-setting-enable" role="button" href="javascript:void(0)" data-name="{{ $type['name'] }}" data-route="{{ route('admin.affiliates.commission-types.enable', $type['id']) }}">
                                            @lang('Enable')
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    @include('affiliates::admin.modals.edit-commission-type')
@endsection
