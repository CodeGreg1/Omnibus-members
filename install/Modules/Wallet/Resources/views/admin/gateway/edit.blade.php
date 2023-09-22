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
            <a href="javascript:void(0)"> {{ $manualGateway->name }}</a>
        </li>
    </ol>
@endsection

@section('content')
    <form id="admin-manual-gateway-edit-form" method="post" data-action="{{ route('admin.manual-gateways.update', $manualGateway) }}">
        <div class="card card-form">
            <div class="card-header">
                <h4>{{ $pageTitle }}</h4>
            </div>
            <input type="hidden" name="redirectTo" value="{{ $backUrl }}">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="media_id">@lang('Image') <span class="text-muted">(@lang('Optional'))</span></label>
                            <div data-image-gallery="manual-gateway-logo-edit" name="media_id">
                                @if ($manualGateway->logo)
                                    <ul class="list-group">
                                        <li class="list-group-item" data-id="{{ $manualGateway->logo->id }}">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="gallery-photo-preview">
                                                        <img class="rounded" src="{{ $manualGateway->logo->preview_url }}" alt="{{ $manualGateway->logo->name }}" data-dz-thumbnail="" data-src="{{ $manualGateway->logo->original_url }}">
                                                    </div>
                                                </div>
                                                <div class="col overflow-hidden">
                                                    <h6 class="text-sm mb-1 image-name" data-name="{{ $manualGateway->logo->name }}">{{ $manualGateway->logo->name }}</h6>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="javascript:void(0)" class="dropdown-item btn-remove-selected-gallery-image"><i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="type" name="name" class="form-control" value="{{ $manualGateway->name }}">
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
                                        @selected($currency->code === $manualGateway->currency)
                                    >{{ $currency->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if ($type === 'withdraw')
                        <div class="col-12">
                            <div class="form-group">
                                <label for="delay">@lang('Processing Time') <span class="text-muted">(@lang('Required'))</span></label>
                                <input type="text" name="delay" class="form-control" placeholder="@lang('1 - 2 hours')" value="{{ $manualGateway->delay }}">
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <h6>@lang('Range')</h6>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="min_limit">@lang('Min limit') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" name="min_limit" class="form-control" value="{{ $manualGateway->min_limit }}" data-decimals="2">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="max_limit">@lang('Max limit') <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" name="max_limit" class="form-control" value="{{ $manualGateway->max_limit }}" data-decimals="2">
                        </div>
                    </div>
                    <div class="col-12">
                        <h6>@lang('Charge')</h6>
                    </div>
                    @php
                        $charge_type = 'fixed';
                        if (!is_null($manualGateway->fixed_charge) && !is_null($manualGateway->percent_charge) ) {
                            $charge_type = 'both';
                        } else {
                            if (!is_null($manualGateway->percent_charge)) {
                                $charge_type = 'percent';
                            }
                        }
                    @endphp
                    <div class="col-12">
                        <div class="form-group">
                            <select name="charge_type" class="form-control">
                                <option value="fixed" @selected($charge_type === 'fixed')>@lang('Fixed')</option>
                                <option value="percent" @selected($charge_type === 'percent')>@lang('Percent')</option>
                                <option value="both" @selected($charge_type === 'both')>@lang('Both')</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 admin-gateway-fixed-charge {{ $charge_type === 'both' ? '' : ($charge_type === 'fixed' ? '' : 'd-none') }}">
                        <div class="form-group">
                            <label class="d-block" for="fixed_charge">@lang('Fixed') <span class="text-muted">(@lang('Required'))</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text manual-gateway-currency-symbol">
                                        {{ currency()->getCurrencyProp($manualGateway->currency, 'symbol', '$') }}
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="fixed_charge" name="fixed_charge" value="{{ $manualGateway->fixed_charge }}" data-decimals="2">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 admin-gateway-percent-charge {{ $charge_type === 'both' ? '' : ($charge_type === 'percent' ? '' : 'd-none') }}">
                        <div class="form-group">
                            <label class="d-block" for="percent_charge">@lang('Percent') <span class="text-muted">(@lang('Required'))</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="percent_charge" name="percent_charge" value="{{ $manualGateway->percent_charge }}" data-decimals="2">
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
                <textarea id="admin-manual-gateway-instructions">{{ $manualGateway->instructions }}</textarea>
            </div>
        </div>

        <div class="card card-form">
            <div class="card-header">
                <h4>@lang('User data')</h4>
                <div class="card-header-action">
                    <a href="javascript:void(0)" class="btn btn-icon icon-left btn-primary" id="btn-admin-add-manual-gateway-user-data-row">
                        <i class="fas fa-plus"></i> Add
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="col-12 px-0" id="admin-manual-gateway-user-data">
                    <div class="text-center py-3 text-muted no-user-data-row {{ $manualGateway->user_data ? (count($manualGateway->user_data) ? 'd-none' : '') : '' }}">No user data</div>
                    @if ($manualGateway->user_data && count($manualGateway->user_data))
                        @foreach ($manualGateway->user_data as $key => $user_data)
                            <div class="row user-data-row" data-id="{{ $key + 1 }}">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <input name="field_name" class="form-control" placeholder="@lang('Field name')" value="{{ $user_data->field_name }}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <select name="field_type" class="form-control">
                                            <option
                                                value="input"
                                                @selected($user_data->field_type === 'input')
                                            >@lang('Input Text')</option>
                                            <option
                                                value="textarea"
                                                @selected($user_data->field_type === 'textarea')
                                            >@lang('Textarea')</option>
                                            <option
                                                value="image_upload"
                                                @selected($user_data->field_type === 'image_upload')
                                            >@lang('Image upload')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <select name="required" class="form-control">
                                            <option
                                                value="true"
                                                @selected($user_data->required)
                                            >@lang('Required')</option>
                                            <option
                                                value="false"
                                                @selected(!$user_data->required)
                                            >@lang('Optional')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-1 text-right">
                                    <a href="javascript:void(0)" class="btn btn-icon btn-block btn-danger btn-remove-admin-manual-gateway-row mb-4" data-id="{{ $key + 1 }}"><i class="fas fa-times"></i></a>
                                </div>
                            </div>
                        @endforeach
                    @endif
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
