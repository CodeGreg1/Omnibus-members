@extends('subscriptions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/tom-select/dist/css/tom-select.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-daterangepicker/css/daterangepicker.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-daterangepicker/js/daterangepicker.js') }}"></script>
    <script src="{{ url('plugins/autosize/dist/autosize.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.subscriptions.pricing-tables.index') }}">@lang('Pricing tables')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="mt-4 mt-lg-0">
        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <form id="createPricingTableForm">
                    <div class="form-group">
                        <label for="name">@lang('Name')</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label for="name">
                            @lang('Description')
                            <span class="text-muted">(@lang('Optional'))</span>
                        </label>
                        <input type="text" class="form-control" name="description" id="description">
                    </div>

                    <h6>@lang('Packages')</h6>
                    <table class="table table-sm" id="pricing-table-packages">
                        <tbody>
                        </tbody>
                    </table>

                    <div class="form-group mb-0">
                        <select id="pricing-table-search-packages" placeholder="@lang('Pick a package...')" multiple></select>
                    </div>

                    <div class="mt-3">
                        <div class="form-group mb-0">
                            <label>@lang('Featured package')</label>
                            <select class="custom-select" id="featured_package" name="featured_package">
                                <option selected="" value="">@lang('Dont set featured package')</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-footer bg-whitesmoke">
                <button type="submit" class="btn btn-primary btn-lg float-right" id="btn-create-pricing-table">@lang('Create')</button>
            </div>
        </div>
    </div>
</div>
@endsection
