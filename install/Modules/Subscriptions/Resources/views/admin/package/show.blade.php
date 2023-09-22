@extends('subscriptions::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="#"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.subscriptions.packages.index') }}"> @lang('Packages')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="{{ route('admin.subscriptions.packages.show', [$package]) }}"> {{ $package->name }}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="mt-4 mt-lg-0">
        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('content')
    <section class="section">
        <input type="hidden" id="Package_id" value="{{ $package->id }}">
        <div class="section-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6>@lang('Pricing')</h6>
                            <div class="card-header-action ml-auto">
                                <a href="#" role="button" class="btn btn-primary" data-toggle="modal" data-target="#create-package-price-modal">@lang('Add price')</a>
                            </div>
                        </div>
                        <div class="card-body p-0 position-relative" id="package-prices-list" data-id="{{ $package->id }}">

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h6>@lang('Features')</h6>
                            <div class="card-header-action ml-auto">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#add-package-feature-modal">@lang('Manage features')</button>
                                    <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">@lang('Toggle Dropdown')</span>
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(119px, 35px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#create-feature-modal">@lang('Create feature')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border mb-0" id="package-feature-reorder-list" data-href="{{ route('admin.subscriptions.packages.reorder-feature', $package) }}">
                                @forelse ($package->features as $feature)
                                    <li class="media">
                                        <span class="package-feature-handle-column mr-4"><span data-id="{{ $feature->id }}" data-ordering="{{ $feature->ordering }}"><i class="fas fa-arrows-alt drag-handle"></i></span></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 text-success" width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="media-body">
                                            <div class="media-title">{{ $feature->title }}</div>
                                        </div>
                                        <button type="button" class="btn btn-icon btn-danger btn-sm btn-remove-package-feature" data-route="{{ route('admin.subscriptions.packages.delete-feature', [$package, $feature]) }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </li>
                                @empty
                                    <li class="no-feature-found">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <p class="text-muted py-4 mb-0">@lang('No features found')</p>
                                        </div>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h6>@lang('Extra conditions')</h6>
                            <div class="card-header-action ml-auto">
                                <a href="#" role="button" class="btn btn-primary" data-toggle="modal" data-target="#create-package-extra-condition-modal">@lang('Add extra condition')</a>
                            </div>
                        </div>
                        <div class="card-body p-0 position-relative" id="package-extra-condition-table" data-id="{{ $package->id }}">

                        </div>
                    </div>

                    @if (count($modulePermissions))
                        <div class="card" id="limits">
                            <div class="card-header">
                                <h4>@lang('Module action limits')</h4>
                            </div>
                            <div class="card-body py-0">
                                <div class="alert alert-info alert-has-icon">
                                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                                    <div class="alert-body">
                                        <div class="alert-title">@lang('Note')</div>
                                        @lang('Selected module with no specified limit has unlimited access to the selected module.')
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>@lang('Module')</th>
                                            <th colspan="2" class="pl-2">@lang('Limit')</th>
                                        </tr>
                                    </thead>
                                    @foreach ($modulePermissions as $modulePermission)
                                        <tr>
                                            <td colspan="3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <strong>{{ $modulePermission['title'] }}</strong>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input package-permission-module-select-all" id="module-{{ $modulePermission['key'] }}" data-module-name="{{ $modulePermission['key'] }}">
                                                        <label class="custom-control-label" for="module-{{ $modulePermission['key'] }}">@lang('Select all')</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($modulePermission['permissions'] as $permission)
                                            @php
                                                $limit = $package->moduleLimits->where('permission_id', $permission->id)->first();
                                            @endphp
                                            <tr>
                                                <td class="pl-4" style="vertical-align: middle;">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input package-permission-module-select" id="permission-{{ $permission->id }}" data-module="{{ $modulePermission['key'] }}" data-id="{{ $permission->id }}" @checked(!!$limit)>
                                                        <label class="custom-control-label" for="permission-{{ $permission->id }}">
                                                            {{ str_replace("Index", "Manage", $permission->display_name) }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="pr-1 pl-2" style="width: 120px;">
                                                    <input type="number" class="form-control package-permission-limit float-right" name="package-permission-limit" data-id="{{ $permission->id }}" style="width: 120px;" @disabled(!$limit) value="{{ $limit ? $limit->limit : null }}"/>
                                                </td>
                                                <td class="pl-1" style="width: 100px;">
                                                    <select class="form-control package-permission-term float-right" style="width: 100px;" data-id="{{ $permission->id }}" @disabled(!$limit)>
                                                        <option value="month" @selected($limit ? ($limit->term === 'month' ? true : false) : false)>@lang('Month')</option>
                                                        <option value="day" @selected($limit ? ($limit->term === 'day' ? true : false) : false)>@lang('Day')</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </table>
                            </div>
                            <div class="card-footer text-right">
                                <button
                                    class="btn btn-primary"
                                    id="btn-update-package-module-limits"
                                    data-route="{{ route('admin.subscriptions.packages.update-module-limits', $package) }}"
                                >@lang('Save')</button>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card {{ $package->is_featured ? 'card-primary' : '' }}">
                        <div class="card-header">
                            <h6>@lang('Package details')</h6>
                            <div class="card-header-action ml-auto">
                                <a href="#" role="button" class="btn btn-primary" data-toggle="modal" data-target="#edit-package-modal">@lang('Edit')</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Name')</label>
                                    <p class="mb-0">{{ $package->name }}</p>
                                </div>
                                <div class="form-group col-12">
                                    <label class="mb-0">@lang('Primary heading')</label>
                                    @if ($package->primary_heading)
                                        <p class="mb-0">{{ $package->primary_heading }}</p>
                                    @else
                                        <p class="mb-0 text-secondary"><em>@lang('None')</em></p>
                                    @endif
                                </div>
                                <div class="form-group col-12 mb-0">
                                    <label class="mb-0">@lang('Secondary heading')</label>
                                    @if ($package->secondary_heading)
                                        <p class="mb-0">{{ $package->secondary_heading }}</p>
                                    @else
                                        <p class="mb-0 text-secondary"><em>@lang('None')</em></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modals')
    @include('subscriptions::admin.modals.edit-package-price')
    @include('subscriptions::admin.modals.add-package-price')
    @include('subscriptions::admin.modals.edit-package')
    @include('subscriptions::admin.modals.add-package-feature')
    @include('subscriptions::admin.modals.create-feature')
    @include('subscriptions::admin.modals.edit-feature')
    @include('subscriptions::admin.modals.edit-package-extra-condition')
    @include('subscriptions::admin.modals.create-package-extra-condition')
@endsection
