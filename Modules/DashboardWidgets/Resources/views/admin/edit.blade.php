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
    
    <div class="row" id="edit-dashboard-widgets">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <div class="card card-form">
                <div class="card-header">
                    <h4>{{ $pageTitle }}</h4>
                </div>
                
                <form id="admin-dashboard-widgets-create-form" method="post">
                    <input type="hidden" name="id" value="{{ $dashboardwidgets->id }}">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <h6 class="mb-4 field-group-heading">@lang('Widget Type') <span class="text-muted">(@lang('Required'))</span></h6>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="line" class="selectgroup-input" {{ $dashboardwidgets->type == 'line' ? 'checked' : '' }}>
                                            <div class="selectgroup-button selectgroup-button-icon">
                                                <i class="fas fa-chart-line"></i>
                                                <p class="font-weight-bold">@lang('Line Chart')</p>
                                            </div>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="bar" class="selectgroup-input" {{ $dashboardwidgets->type == 'bar' ? 'checked' : '' }}>
                                            <div class="selectgroup-button selectgroup-button-icon">
                                                <i class="fas fa-chart-bar"></i>
                                                <p class="font-weight-bold">@lang('Bar Chart')</p>
                                            </div>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="pie" class="selectgroup-input" {{ $dashboardwidgets->type == 'pie' ? 'checked' : '' }}>
                                            <div class="selectgroup-button selectgroup-button-icon">
                                                <i class="fas fa-chart-pie"></i>
                                                <p class="font-weight-bold">@lang('Pie Chart')</p>
                                            </div>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="counter" class="selectgroup-input" {{ $dashboardwidgets->type == 'counter' ? 'checked' : '' }}>
                                            <div class="selectgroup-button selectgroup-button-icon">
                                                <i class="fas fa-calculator"></i>
                                                <p class="font-weight-bold">@lang('Counter')</p>
                                            </div>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="latest_records" class="selectgroup-input" {{ $dashboardwidgets->type == 'latest_records' ? 'checked' : '' }}>
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
                                                        <option value="{{ $modelGroup['namespace'] }}" {{$modelGroup['namespace']==$attributes['model']?'selected':''}}>{{ $modelGroup['model_name'] }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @php
                                        $dataAggregatingFunctionAndGroupByColumnOptions = \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::getDataAggregatingFunctionAndGroupByColumnOptions($attributes['model']);
                                    @endphp

                                    <div class="col-lg-3 aggregate-function-wrapper {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::visibility($dashboardwidgets->type, 'aggregate_function')}}">
                                        <div class="form-group">
                                            <label for="model">@lang('Data Aggregating Function/Column')</label>
                                            <select class="form-control select2" name="aggregate_function" {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::disabledField($dashboardwidgets->type, 'aggregate_function')}}>
                                                @if(count($dataAggregatingFunctionAndGroupByColumnOptions['aggregate_function_options']))
                                                    @foreach($dataAggregatingFunctionAndGroupByColumnOptions['aggregate_function_options'] as $optionKey => $optionValue)
                                                        <option 
                                                        value="{{$optionKey}}"
                                                        {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::selectedDataAggregatingFunction($attributes, $optionKey) }}>{{$optionValue}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">@lang('Select Model')</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 group-by-column-wrapper {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::visibility($dashboardwidgets->type, 'group_by_column')}}">
                                        
                                        <div class="form-group">
                                            <label for="group_by_column">@lang('Group by Column')</label>
                                            <select class="form-control select2" name="group_by_column" {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::disabledField($dashboardwidgets->type, 'group_by_column')}}>
                                                @if(count($dataAggregatingFunctionAndGroupByColumnOptions['group_by_column_options']))
                                                    @foreach($dataAggregatingFunctionAndGroupByColumnOptions['group_by_column_options'] as $optionKey => $optionValue)
                                                        <option 
                                                        value="{{$optionKey}}"
                                                        {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::selectedGroupByColumn($attributes, $optionKey) }}>{{$optionValue}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">@lang('Select Model')</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 filter-field-wrapper {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::visibility($dashboardwidgets->type, 'filter_field')}}">
                                        <div class="form-group">
                                            <label for="filter_field">@lang('Filter Field')</label>
                                            <select class="form-control select2" name="filter_field" {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::disabledField($dashboardwidgets->type, 'filter_field')}}>
                                                <option value="">@lang('Select Model')</option>
                                                @foreach(\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::getFilterFieldOptions($attributes['model']) as $option)
                                                    <option 
                                                        value="{{ $option }}"
                                                        {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::selectedFilterField($attributes, $option) }}>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 total-records-wrapper {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::visibility($dashboardwidgets->type, 'total_records')}}">
                                        <div class="form-group">
                                            <label for="model">@lang('Total Records to Show')</label>
                                            <select class="form-control select2" name="total_records" {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::disabledField($dashboardwidgets->type, 'total_records')}}>
                                                <option value="5" {{ (isset($attributes['total_records']) && $attributes['total_records'] == 5)?'selected':'' }}>5</option>
                                                <option value="10" {{ (isset($attributes['total_records']) && $attributes['total_records'] == 10)?'selected':'' }}>10</option>
                                                <option value="20" {{ (isset($attributes['total_records']) && $attributes['total_records'] == 20)?'selected':'' }}>20</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 show-filter-data-wrapper {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::visibility($dashboardwidgets->type, 'filter_data')}}">
                                        <div class="form-group">
                                            <label for="filter_data">@lang('Show Filtered Data') (by <span class='field-name'>created_at</span>)</label>
                                            <select data-edit-value="{{ isset($attributes['filter_data']) ? $attributes['filter_data'] : '' }}" class="form-control select2" name="filter_data" {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::disabledField($dashboardwidgets->type, 'filter_data')}}>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 show-blank-data-wrapper {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::visibility($dashboardwidgets->type, 'show_blank_data')}}">
                                        <div class="form-group">
                                            <label for="model">@lang('Show Blank Data')</label>
                                            <select class="form-control select2" name="show_blank_data" {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::disabledField($dashboardwidgets->type, 'show_blank_data')}}>
                                                <option value="no" {{ isset($attributes['show_blank_data']) ? 'selected' : '' }}>@lang('No')</option>
                                                <option value="yes" {{ isset($attributes['show_blank_data']) ? 'selected' : '' }}>@lang('Yes')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 load-relationships-wrapper {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::visibility($dashboardwidgets->type, 'loaded_relationships')}}">
                                        <div class="form-group">
                                            <label for="loaded_relationships">@lang('Load relationships')</label>
                                            <input class="form-control" name="loaded_relationships" placeholder="@lang('Add relationship name separated by comma.')" value="{{$loadedRelationships}}" {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::disabledField($dashboardwidgets->type, 'loaded_relationships')}}>
                                            <small class="form-text text-muted form-help"><strong>@lang('Note'):</strong> @lang('Add relationships that loaded in your selected model above and it should be separated by comma. The relationship names you added must define in your model if not it will cause an error.')</small>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 field-to-show-table-wrapper {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::visibility($dashboardwidgets->type, 'fields_to_show')}}">
                                        <div class="table-responsive">
                                            <table data-fields="{{ isset($attributes['fields']) ? json_encode($attributes['fields']) : '' }}" class="table table-striped dashboard-widgets-field-relationship-table" id="fields-table-to-show" style="width: 2000px">
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
                                                    @foreach($tableColumns as $column => $columnSettings)
                                                        <tr class="row-field-to-show {{$column}}-field-row">
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input {{ isset($attributes['fields']) && in_array($column, array_keys($attributes['fields'])) ? 'checked' : '' }} type="checkbox" name="checkbox_field" class="custom-control-input checkbox-field-to-show" id="custom-check-{{$column}}">
                                                                    <label class="custom-control-label" for="custom-check-{{$column}}"></label>
                                                                </div>
                                                            </td>
                                                            
                                                            <td>{{$column}}</td>

                                                            <td>
                                                                <input {{ isset($attributes['fields']) && in_array($column, array_keys($attributes['fields'])) ? '' : 'disabled' }} type="text" value="{{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::columnNameValue($attributes, $column, $columnSettings['display']) }}" name="fields[{{$column}}][column_name]" class="form-control field-to-show-display">
                                                            </td>

                                                            <td>
                                                                <select {{ isset($attributes['fields']) && in_array($column, array_keys($attributes['fields'])) ? '' : 'disabled' }} class="form-control select2 field-to-show-format-type" name="fields[{{$column}}][format_type]">
                                                                    @foreach(\Modules\DashboardWidgets\Services\Helper\WidgetHelper::$formatTypes as $formatTypeKey => $formatTypeValue)
                                                                        <option value="{{$formatTypeKey}}" {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::selectedFormatType($attributes, $column, $formatTypeKey) }}>{{$formatTypeValue}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <textarea {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::isDisabledDisplayFormat($attributes, $column) }}  
                                                                    name="fields[{{$column}}][display_format]" 
                                                                    class="form-control field-to-show-display-format"
                                                                    data-default-value="{{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::getDisplayFormatDefaultValue($attributes, $column) }}">{{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::displayFormatValue($attributes, $column) }}</textarea>
                                                            </td>

                                                            <td>
                                                                <input 
                                                                    {{ isset($attributes['fields']) && in_array($column, array_keys($attributes['fields'])) ? '' : 'disabled' }} 
                                                                    type="number" 
                                                                    maxlength="100" 
                                                                    placeholder="@lang('Column width in percentage.')" 
                                                                    name="fields[{{$column}}][width]" 
                                                                    class="form-control field-to-show-width" 
                                                                    value="{{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::widthValue($attributes, $column) }}">
                                                            </td>

                                                            <td>
                                                                <select {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::isDisabledFieldToShowRelationshipsField($attributes, $column) }} class="field-to-show-relationship-name form-control select2" name="fields[{{$column}}][relationship_name]">
                                                                    @if($loadedRelationships != '')
                                                                        <option value="">@lang('Select Relationship Name')</option>
                                                                        @foreach(explode(',', $loadedRelationships) as $value)
                                                                            <option value="{{$value}}" {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::selectedRelationshipName($attributes, $column, $value) }}>{{$value}}</option>
                                                                        @endforeach
                                                                    @else
                                                                        <option value="">@lang('Add Loaded Relationships')</option>
                                                                    @endif
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <select {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::isDisabledFieldToShowRelationshipsField($attributes, $column) }} class="field-to-show-relationship-model form-control select2" name="fields[{{$column}}][relationship_model]">
                                                                    @if($loadedRelationships != '')
                                                                        <option value="">@lang('Select Model')</option>
                                                                        @foreach($models as $modelGroups)
                                                                            @foreach($modelGroups as $modelGroup)
                                                                                <option 
                                                                                    value="{{ $modelGroup['namespace'] }}" 
                                                                                    {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::selectedRelationshipModel($attributes, $column, $modelGroup['namespace']) }}>{{ $modelGroup['model_name'] }}</option>
                                                                            @endforeach
                                                                        @endforeach
                                                                    @else
                                                                        <option value="">@lang('Add Loaded Relationships')</option>
                                                                    @endif
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <select {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::isDisabledFieldToShowRelationshipsField($attributes, $column) }} multiple class="field-to-show-relationship-fields form-control" name="fields[{{$column}}][relationship_fields][]">
                                                                    @if($loadedRelationships != '')
                                                                        <option value="">@lang('Select Relationship Fields')</option>
                                                                        @if(\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::getTableColumns($attributes, $column) !== null)
                                                                            @foreach(\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::getTableColumns($attributes, $column) as $columnkey=>$columnSettings)
                                                                                <option value="{{ $columnkey }}" {{ \Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::selectedRelationshipFields($attributes, $column, $columnkey) }}>{{ $columnkey }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    @else
                                                                        <option value="">@lang('Add Loaded Relationships')</option>
                                                                    @endif
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    @endforeach
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
                                                <option value="bg-primary" {{ isset($attributes['background_class']) && $attributes['background_class'] == 'bg-primary' ? 'selected' : '' }}>@lang('Primary')</option>
                                                <option value="bg-info" {{ isset($attributes['background_class']) && $attributes['background_class'] == 'bg-info' ? 'selected' : '' }}>@lang('Info')</option>
                                                <option value="bg-success" {{ isset($attributes['background_class']) && $attributes['background_class'] == 'bg-success' ? 'selected' : '' }}>@lang('Success')</option>
                                                <option value="bg-warning" {{ isset($attributes['background_class']) && $attributes['background_class'] == 'bg-warning' ? 'selected' : '' }}>@lang('Warning')</option>
                                                <option value="bg-danger" {{ isset($attributes['background_class']) && $attributes['background_class'] == 'bg-danger' ? 'selected' : '' }}>@lang('Danger')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-3 col-md-3">
                                        <div class="form-group">
                                            <label for="model">@lang('Icon')</label>

                                            <select name="icon" class="form-control icon-widget-select2" disabled>
                                                @foreach(fontawesome_all() as $fa)
                                                    <option value="{{ $fa }}" {{ isset($attributes['icon']) && $attributes['icon'] == $fa ? 'selected' : '' }}>{{ $fa }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-3 col-md-3">
                                        <div class="form-group">
                                            <label for="view_more">@lang('View More Button Route')</label>

                                            <select name="view_more" class="form-control select2" {{\Modules\DashboardWidgets\Services\Helper\EditWidgetHelper::disabledField($dashboardwidgets->type, 'view_more')}}>
                                                @foreach($routes as $route)
                                                    <option value="{{ $route }}" {{  isset($attributes['view_more']) && $attributes['view_more'] == $route ? 'selected' : ''  }}>{{ $route }}</option>
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
                                                value="{{ $dashboardwidgets->name }}" 
                                                name="name"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-6">
                                        <div class="form-group">
                                            <label for="type">@lang('Width') <span class="text-muted">(@lang('Required'))</span></label>
                                            <select class="form-control select2" name="width">
                                                <option value="col-lg-12" {{ ($attributes['column_class'] == 'col-lg-12') ? 'selected' : '' }}>@lang('Full row (col-lg-12)')</option>
                                                <option value="col-lg-6" {{ ($attributes['column_class'] == 'col-lg-6') ? 'selected' : '' }}>@lang('1/2 - half of row (col-lg-6)')</option>
                                                <option value="col-lg-4" {{ ($attributes['column_class'] == 'col-lg-4') ? 'selected' : '' }}>@lang('1/3 - one-third of row (col-lg-4)')</option>
                                                <option value="col-lg-3" {{ ($attributes['column_class'] == 'col-lg-3') ? 'selected' : '' }}>@lang('1/4 - one-fourth of row (col-lg-3)')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
                        <a href="{{ route('admin.dashboard-widgets.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>    



            </div>
        </div>
    </div>
    

@endsection
