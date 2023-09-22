@extends('wallet::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
    <script src="{{ url('plugins/tinymce/js/tinymce.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ $backUrl ?? '#' }}"> {{ $methodTitle }}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('New')</a>
        </li>
    </ol>
@endsection

@section('content')
    <form id="admin-manual-gateway-create-form" method="post">
        <div class="card card-form">
            <div class="card-header">
                <h4>{{ $pageTitle }}</h4>
            </div>
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="redirectTo" value="{{ $backUrl }}">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="media_id">@lang('Image') <span class="text-muted">(@lang('Optional'))</span></label>
                            <div data-image-gallery="manual-gateway-logo-create" name="media_id">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="currency">@lang('Currency') <span class="text-muted">(@lang('Required'))</span></label>
                            <select class="form-control" id="currency" name="currency" @if ($currencies->count() === 1)
                                readonly
                            @endif>
                                @foreach ($currencies as $currency)
                                    <option
                                        value="{{ $currency->code }}"
                                        data-symbol="{{ $currency->symbol }}"
                                    >{{ $currency->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($type === 'withdraw')
                        <div class="col-12">
                            <div class="form-group">
                                <label for="delay">@lang('Processing Time') <span class="text-muted">(@lang('Required'))</span></label>
                                <input type="text" name="delay" class="form-control" placeholder="@lang('1 - 2 hours')">
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <h6>@lang('Range')</h6>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="min_limit">@lang('Min limit') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" name="min_limit" class="form-control" data-decimals="2">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="max_limit">@lang('Max limit') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" name="max_limit" class="form-control" data-decimals="2">
                        </div>
                    </div>
                    <div class="col-12">
                        <h6>@lang('Charge')</h6>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <select name="charge_type" class="form-control">
                                <option value="fixed">@lang('Fixed')</option>
                                <option value="percent">@lang('Percent')</option>
                                <option value="both">@lang('Both')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 admin-gateway-fixed-charge">
                        <div class="form-group mb-0 mb-md-4">
                            <label class="d-block" for="fixed_charge">@lang('Fixed') <span class="text-muted">(@lang('Required'))</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text manual-gateway-currency-symbol">
                                        {{ $currencies[0]->symbol }}
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="fixed_charge" name="fixed_charge" data-decimals="2">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 admin-gateway-percent-charge d-none">
                        <div class="form-group mb-0 mb-md-4">
                            <label class="d-block" for="percent_charge">@lang('Percent') <span class="text-muted">(@lang('Required'))</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="percent_charge" name="percent_charge" data-decimals="2">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-form">
            <div class="card-header">
                <h4>@lang('Instructions')</h4>
            </div>
            <div class="card-body">
                <textarea id="admin-manual-gateway-instructions"></textarea>
            </div>
        </div>

        <div class="card card-form">
            <div class="card-header">
                <h4>@lang('User data')</h4>
                <div class="card-header-action">
                    <a href="javascript:void(0)" class="btn btn-icon icon-left btn-primary" id="btn-admin-add-manual-gateway-user-data-row">
                        <i class="fas fa-plus"></i> @lang('Add')
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="col-12 px-0" id="admin-manual-gateway-user-data">
                    <div class="text-center py-3 text-muted no-user-data-row">@lang('No user data')</div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-end">
            <a href="{{ $backUrl ?? '#' }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
            <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
        </div>
    </form>
@endsection

@section('modals')
    @include('photos::modals.select-image')
@endsection
