@extends('subscriptions::layouts.master')

@section('module-styles')

@endsection

@section('scripts')
    <script>
    var terms = @json($terms);
    var currency = @json($currency);
    var currencies = @json($currencies);
    </script>
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/sticky-sidebar/js/jquery.sticky-sidebar.modified.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
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
            <a href="#"> @lang('New package')</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="mt-4 mt-lg-0">
        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('content')
    <div class="row" id="package-form">
        <div class="col-md-4 d-none d-sm-block wrapper-main">
            <div class="sidebar-item">
                <div class="make-me-sticky">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-columns"></i> @lang('Sections')</h4>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column package-sections">
                                <li class="nav-item"><a href="#general" class="nav-link active"><i class="fas fa-flag"></i> @lang('General settings')</a></li>
                                <li class="nav-item"><a href="#pricing" class="nav-link"><i class="fas fa-share"></i> @lang('Pricing details')</a></li>
                                <li class="nav-item"><a href="#features" class="nav-link"><i class="fa fa-briefcase"></i> @lang('Features')</a></li>
                                <li class="nav-item"><a href="#extra-conditions" class="nav-link"><i class="fa fa-briefcase"></i> @lang('Extra conditions')</a></li>
                                @if (count($modulePermissions))
                                    <li class="nav-item"><a href="#limits" class="nav-link"><i class="fa fa-clock"></i> @lang('Module limits')</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-form" id="general">
                <div class="card-header">
                    <h4>@lang('Package information')</h4>
                </div>

                <form id="package-information" method="post">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">@lang('Name')</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="@lang('Premium Plan')">
                        </div>
                        <a class="fw-bold" data-toggle="collapse" href="#additionalOptionsCollapse" role="button" aria-expanded="true" aria-controls="additionalOptionsCollapse">
                            <span>@lang('Additional options')<span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="collapse mt-3" id="additionalOptionsCollapse" style="">
                            <div class="form-group">
                                <label for="primary_heading">@lang('Primary heading')</label>
                                <input type="text" class="form-control" name="primary_heading" id="primary_heading">
                            </div>
                            <div class="form-group mb-0">
                                <label for="secondary_heading">@lang('Secondary heading')</label>
                                <input type="text" class="form-control" name="secondary_heading" id="secondary_heading">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card" id="pricing">
                <div class="card-header">
                    <h4>@lang('Pricing details')</h4>
                </div>
                <div class="card-body pt-0">
                    <div id="package-prices">
                        <div class="accordion border-top pt-3" id="package-pricing-1" data-id="1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center justify-content-between flex-grow-1 cursor-pointer" role="button" data-toggle="collapse" data-target="#package-price-1" aria-expanded="true">
                                    <h6 class="price-title">@lang('Pricing details')</h6>
                                    <i class="fa fa-angle-down"></i>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger ml-3 btn-remove-package-pricing d-none" data-id="1">
                                    <h6 class="mb-0">&times;</h6>
                                </button>
                            </div>

                            <div class="accordion-body collapse show px-0 pb-0" id="package-price-1" data-parent="#package-prices" style="">
                                <div class="form-row">
                                    <div class="form-group col-md-7">
                                        <label for="pricing-1-price">@lang('Price')</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <select class="custom-select" id="pricing-1-currency" name="pricing-1-currency" style="width:100px;">
                                                    @foreach ($currencies as $currencyItem)
                                                        <option
                                                            value="{{ $currencyItem->code }}"
                                                            @selected($currencyItem->code === $currency)
                                                        >{{ $currencyItem->code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="text" class="form-control" id="pricing-1-price" name="pricing-1-price" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="pricing-1-compare-price">@lang('Compare at price')</label>
                                        <input type="text" class="form-control" id="pricing-1-compare-price" name="pricing-1-compare-price" placeholder="0.00">
                                    </div>
                                </div>
                                <ul class="nav nav-pills" id="planTypePill-1" role="tablist">
                                    <li class="nav-item">
                                        <a class="planType nav-link active show" data-toggle="tab" href="#pricing-1-recurring-payment" role="tab" aria-controls="pricing-1-recurring-payment" data-id="recurring" aria-selected="true">@lang('Recurring')</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="planType nav-link" data-toggle="tab" href="#pricing-1-onetime-payment" role="tab" aria-controls="pricing-1-onetime-payment" aria-selected="false" data-id="onetime">@lang('One time')</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="planTypeTab-1">
                                    <div class="tab-pane fade active show pb-0" id="pricing-1-recurring-payment" role="tabpanel" aria-labelledby="pricing-1-recurring-payment">
                                        <div class="form-group">
                                            <label for="pricing-1-term">@lang('Term')</label>
                                            <select class="form-control select2" id="pricing-1-term" name="pricing-1-term">
                                                @foreach ($terms as $term)
                                                    <option value="{{ $term->id }}">{{ $term->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label for="pricing-1-trial_days_count">@lang('Free trial')</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="pricing-1-trial_days_count" name="pricing-1-trial_days_count" placeholder="0">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">@lang('days')</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pricing-1-onetime-payment" role="tabpanel" aria-labelledby="pricing-1-onetime-payment">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="mt-3 btn btn-sm btn-primary btn-add-package-pricing">@lang('Add new price')</button>
                </div>
            </div>

            <div class="card" id="features">
                <div class="card-header">
                    <h4>@lang('Features')</h4>
                    <div class="card-header-action ml-auto">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-feature-modal">@lang('Create feature')</button>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border package-create-feature-list mb-0" id="package-feature-reorder-list">
                        @forelse ($features as $feature)
                            <li class="media align-items-center">
                                <span class="package-feature-handle-column mr-4"><span data-id="{{ $feature->id }}" data-ordering="{{ $feature->ordering }}"><i class="fas fa-arrows-alt drag-handle"></i></span></span>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input package-feature" id="feature-{{ $feature->id }}" data-id="{{ $feature->id }}">
                                    <label class="custom-control-label" for="feature-{{ $feature->id }}"></label>
                                </div>
                                <div class="media-body">
                                    <h6 class="media-title mb-0">{{ $feature->title }}</h6>
                                </div>
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

            <div class="card" id="extra-conditions">
                <div class="card-header">
                    <h4>@lang('Extra conditions')</h4>
                </div>
                <div class="card-body pt-0">
                    <div id="package-extra-conditions">
                    </div>
                    <div class="py-4 text-center w-100" id="no-package-extra-condition">
                        @lang('No extra conditions set')
                    </div>
                    <button class="btn btn-sm btn-primary btn-add-package-extra-condition">@lang('Add new condition')</button>
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
                    <div class="card-body p-0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Module')</th>
                                    <th colspan="2" class="pl-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>@lang('Limit')</span>
                                            <input type="number" class="form-control package-permission-module-limit-all ml-2" style="width: 100px;"/>
                                        </div>
                                    </th>
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
                                    <tr>
                                        <td class="pl-4" style="vertical-align: middle;">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input package-permission-module-select" id="permission-{{ $permission->id }}" data-module="{{ $modulePermission['key'] }}" data-id="{{ $permission->id }}">
                                                <label class="custom-control-label" for="permission-{{ $permission->id }}">
                                                    {{ str_replace("Index", "Manage", $permission->display_name) }}
                                                </label>
                                            </div>
                                        </td>
                                        <td class="pr-1 pl-2" style="width: 120px;">
                                            <input type="number" class="form-control package-permission-limit float-right" data-id="{{ $permission->id }}" style="width: 120px;" @disabled(true)/>
                                        </td>
                                        <td class="pl-1" style="width: 100px;">
                                            <select class="form-control package-permission-term float-right" style="width: 100px;" data-id="{{ $permission->id }}" @disabled(true)>
                                                <option value="month">@lang('Month')</option>
                                                <option value="day">@lang('Day')</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif

        </div>

        <div class="col mb-2 mt-1">
            <div class="d-flex align-items-center justify-content-end">
                <a href="{{ route('admin.subscriptions.packages.index') }}" class="btn btn-cancel mr-2 btn-lg">@lang('Cancel')</a>
                <button type="submit" class="btn btn-primary btn-lg" id="btn-create-package">@lang('Create')</button>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('subscriptions::admin.modals.create-feature')
@endsection
