@extends('products::layouts.master')

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
            <a href="{{ route('user.products.index') }}"> @lang('Products')</a>
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
                
                <form id="user-products-create-form" method="post">
                    <div class="card-body">
                        <div class="row">

                            
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="price">@lang('Price') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="number" 
                                        value="0"
                                        name="price"
                                        class="form-control"
                                        step="1" required>
                                    <small class='form-text text-muted form-help'>@lang('Price in cents')</small>
                                </div>
                            </div>


                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="currency_id_id">@lang('Currency') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="currency_id_id" class="form-control select2" required>
                                        <option value="">@lang('Select currency')</option>
                                        @foreach($currencyIds as $id => $entry)
                                            <option value="{{ $id }}">{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="title">@lang('Title') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text"
                                        name="title"
                                        class="form-control" required>
                                    <small class='form-text text-muted form-help'>@lang('Title')</small>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('user.products.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>

            </div>
        </div>
    </div>
    

@endsection