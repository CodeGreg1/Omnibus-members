@extends('availablecurrencies::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.available-currencies.index') }}"> @lang('Currencies')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    
    <div class="row">
        <div class="col-lg-6 col-md-6 col-xm-12">
            <div class="card card-form">
                <div class="card-header">
                    <h4>{{ $pageTitle }}</h4>
                </div>
                
                <form id="admin-available-currencies-create-form" method="post">
                    <div class="card-body">
                        <div class="row">

                            

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="currency_id">@lang('Currency') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="currency_id" class="form-control select2" required>
                                        <option value="">@lang('Select currency')</option>
                                        @foreach($currencies as $id => $entry)
                                            <option value="{{ $id }}">{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text"
                                        name="name"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="symbol">@lang('Symbol') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text"
                                        name="symbol"
                                        class="form-control" required readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="code">@lang('Code') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text"
                                        name="code"
                                        class="form-control" required readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="exchange_rate">@lang('Exchange Rate') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text"
                                        name="exchange_rate"
                                        class="form-control"
                                        data-decimals="6"
                                        step="0.01" required>
                                </div>
                            </div>


                            <div class="col-12 col-md-12">

                                <div class="form-group">
                                    <label for="format">@lang('Format') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text"
                                        name="format"
                                        class="form-control" required>
                                </div>
                            </div>


                            <div class="col-12 col-md-12 {{ env('APP_DEMO') ? 'd-none' : '' }}">

                                <div class="form-group">
                                    <label for="system_currency">@lang('System currency') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select class="form-control select2" name="system_currency" required>
                                        <option value="">@lang('Select status')</option>
                                        <option value="1">Yes</option>
                                        <option value="0" selected>No</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-12 col-md-12">

                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                            name="status"
                                            class="custom-control-input" 
                                            id="status"
                                            value="1">
                                        <label class="custom-control-label" for="status">@lang('Status') <span class="text-muted">(@lang('Optional'))</span></label>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.available-currencies.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>

            </div>
        </div>
    </div>
    

@endsection