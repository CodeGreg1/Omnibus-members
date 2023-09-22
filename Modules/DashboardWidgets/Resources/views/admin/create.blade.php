@extends('dashboardwidgets::layouts.master')

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
            <a href="{{ route('admin.dashboard-widgets.index') }}"> @lang('Dashboard widgets')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card card-form">
                <div class="card-header">
                    <h4>{{ $pageTitle }}</h4>
                </div>
                
                <form id="admin-dashboard-widgets-create-form" method="post" autocomplete="off">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <h6 class="mb-4 field-group-heading">@lang('Widget Type') <span class="text-muted">(@lang('Required'))</span></h6>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="line" class="selectgroup-input" checked="">
                                            <div class="selectgroup-button selectgroup-button-icon">
                                                <i class="fas fa-chart-line"></i>
                                                <p class="font-weight-bold">@lang('Line Chart')</p>
                                            </div>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="bar" class="selectgroup-input">
                                            <div class="selectgroup-button selectgroup-button-icon">
                                                <i class="fas fa-chart-bar"></i>
                                                <p class="font-weight-bold">@lang('Bar Chart')</p>
                                            </div>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="pie" class="selectgroup-input">
                                            <div class="selectgroup-button selectgroup-button-icon">
                                                <i class="fas fa-chart-pie"></i>
                                                <p class="font-weight-bold">@lang('Pie Chart')</p>
                                            </div>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="counter" class="selectgroup-input">
                                            <div class="selectgroup-button selectgroup-button-icon">
                                                <i class="fas fa-calculator"></i>
                                                <p class="font-weight-bold">@lang('Counter')</p>
                                            </div>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="latest_records" class="selectgroup-input">
                                            <div class="selectgroup-button selectgroup-button-icon">
                                                <i class="fas fa-list-ol"></i>
                                                <p class="font-weight-bold">@lang('Latest Records')</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>



                            <div class="col-12 col-md-12">
                                <hr/>

                                <h6 class="mb-4 field-group-heading">@lang('Data Source') <span class="text-muted">(@lang('Required'))</span></h6>

                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="model">@lang('Model')</label>
                                            <select class="form-control select2" name="data_source_model">
                                                <option value="">@lang('Select Model')</option>
                                                @foreach($models as $modelGroups)
                                                    @foreach($modelGroups as $modelGroup)
                                                        <option value="{{ $modelGroup['namespace'] }}">{{ $modelGroup['model_name'] }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 aggregate-function-wrapper">
                                        <div class="form-group">
                                            <label for="model">@lang('Data Aggregating Function/Column')</label>
                                            <select class="form-control select2" name="aggregate_function">
                                                <option value="">@lang('Select Model')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 group-by-column-wrapper">
                                        <div class="form-group">
                                            <label for="group_by_column">@lang('Group by Column')</label>
                                            <select class="form-control select2" name="group_by_column">
                                                <option value="">@lang('Select Model')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 filter-field-wrapper display-none">
                                        <div class="form-group">
                                            <label for="filter_field">@lang('Filter Field')</label>
                                            <select class="form-control select2" name="filter_field" disabled>
                                                <option value="">@lang('Select Model')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 total-records-wrapper display-none">
                                        <div class="form-group">
                                            <label for="model">@lang('Total Records to Show')</label>
                                            <select class="form-control select2" name="total_records" disabled>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 show-filter-data-wrapper">
                                        <div class="form-group">
                                            <label for="filter_data">@lang('Show Filtered Data') (by <span class='field-name'>created_at</span>)</label>
                                            <select class="form-control select2" name="filter_data">
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 show-blank-data-wrapper">
                                        <div class="form-group">
                                            <label for="model">@lang('Show Blank Data')</label>
                                            <select class="form-control select2" name="show_blank_data">
                                                <option value="no">@lang('No')</option>
                                                <option value="yes">@lang('Yes')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 load-relationships-wrapper display-none">
                                        <div class="form-group">
                                            <label for="loaded_relationships">@lang('Load relationships')</label>
                                            <input class="form-control" name="loaded_relationships" placeholder="@lang('Add relationship name separated by comma.')" disabled>
                                            <small class="form-text text-muted form-help"><strong>@lang('Note'):</strong> @lang('Add relationships that loaded in your selected model above and it should be separated by comma. The relationship names you added must define in your model if not it will cause an error.')</small>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 field-to-show-table-wrapper display-none">
                                        <div class="table-responsive">
                                            <table class="table table-striped dashboard-widgets-field-relationship-table" id="fields-table-to-show" style="width: 2000px">
                                                <thead>
                                                    <tr>
                                                        <th width="1%">&nbsp;</th>
                                                        <th style="width: 250px">@lang('Database Column')</th>
                                                        <th style="width: 250px">@lang('Column Name')</th>
                                                        <th style="width: 250px">@lang('Format Type')</th>
                                                        <th style="width: 400px">@lang('Display Format')</th>
                                                        <th style="width: 200px">@lang('% Column Width')</th>
                                                        <th style="width: 300px">@lang('Relationship Name')</th>
                                                        <th style="width: 300px">@lang('Relationship Model')</th>
                                                        <th style="width: 300px">@lang('Relationship Fields')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <hr/>
                                <h6 class="mb-4 field-group-heading">@lang('Extra Options') <span class="text-muted">(@lang('Optional'))</span></h6>

                                <div class="row">
                                    <div class="col-3 col-md-3">
                                        <div class="form-group">
                                            <label for="model">@lang('Background Color')</label>
                                            <select class="form-control select2" name="bg_background" disabled>
                                                <option value="bg-primary">@lang('Primary')</option>
                                                <option value="bg-info">@lang('Info')</option>
                                                <option value="bg-success">@lang('Success')</option>
                                                <option value="bg-warning">@lang('Warning')</option>
                                                <option value="bg-danger">@lang('Danger')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-3 col-md-3">
                                        <div class="form-group">
                                            <label for="model">@lang('Icon')</label>

                                            <select name="icon" class="form-control icon-widget-select2" disabled>
                                                @foreach(fontawesome_all() as $fa)
                                                    <option value="{{ $fa }}">{{ $fa }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-3 col-md-3">
                                        <div class="form-group">
                                            <label for="view_more">@lang('View More Button Route')</label>

                                            <select name="view_more" class="form-control select2" disabled>
                                                @foreach($routes as $route)
                                                    <option value="{{ $route }}">{{ $route }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <hr/>
                                <h6 class="mb-4 field-group-heading">@lang('Visual Style') <span class="text-muted">(@lang('Required'))</span></h6>
                            
                                <div class="row">
                                    <div class="col-6 col-md-6">
                                        <div class="form-group">
                                            <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                                            <input type="text"
                                                name="name"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-6">
                                        <div class="form-group">
                                            <label for="type">@lang('Width') <span class="text-muted">(@lang('Required'))</span></label>
                                            <select class="form-control select2" name="width">
                                                <option value="col-lg-12">@lang('Full row (col-lg-12)')</option>
                                                <option value="col-lg-6">@lang('1/2 - half of row (col-lg-6)')</option>
                                                <option value="col-lg-4">@lang('1/3 - one-third of row (col-lg-4)')</option>
                                                <option value="col-lg-3">@lang('1/4 - one-fourth of row (col-lg-3)')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.dashboard-widgets.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>

            </div>
        </div>
    </div>
    

@endsection