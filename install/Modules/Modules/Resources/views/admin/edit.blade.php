@extends('roles::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.modules.index') }}"> @lang('Modules')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xm-12">
            <form id="modules-create-form" method="post">
                <input type="hidden" name="id" value="{{ request()->route('id') }}">

                <div class="card card-form">
                    <div class="card-header">
                        <h4>@lang('Settings')</h4>
                    </div>
                
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">@lang('Module Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text" 
                                        name="module_name"
                                        class="form-control"
                                        value="{{ $module['module_name'] }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-3 col-md-3">
                                <div class="form-group">
                                    <label for="name">@lang('Model Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text" 
                                        name="model_name"
                                        class="form-control"
                                        value="{{ $module['model_name'] }}" readonly>
                                </div>
                            </div>

                            <div class="col-3 col-md-3">
                                
                                <div class="form-group">
                                    <label for="description">@lang('Menu Title and Icon') <span class="text-muted">(@lang('Required'))</span></label>

                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <button class="btn btn-primary" data-icon="fas fa-map-marker-alt" id='menu-icon-picker' type="button"></button>
                                        </span>
                                        <input type="text" 
                                            name="menu_title[name]"
                                            class="form-control"
                                            value="{{ $module['menu_title']['name'] }}">
                                        <input type="text" 
                                            name="menu_title[icon]"
                                            class="form-control display-none"
                                            value="{{ $module['menu_title']['icon'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-3 col-md-3">
                                <div class="form-group">
                                    <label for="description">@lang('Parent Menu') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select 
                                        name="parent_menu" 
                                        class="form-control select2"> 
                                        <option value="">@lang('No parent')</option>
                                        @foreach($menuLinks as $menuLink)
                                            @if(Route::has($menuLink->link))
                                                <option value="{{ $menuLink->id }}" {{ $module['parent_menu'] == $menuLink->id ? 'selected' : '' }}>{{ $menuLink->label }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted form-help">@lang('For admin panel menu only.')</small>
                                </div>
                            </div>

                            <div class="col-3 col-md-3">
                                <div class="form-group">
                                    <label for="description">@lang('Roles') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select 
                                        name="roles[]" 
                                        class="form-control select2" 
                                        data-placeholder="@lang('Select roles')"
                                        multiple>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ in_array($role->id, $module['roles']) ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted form-help">@lang('Select roles to assign this module.')</small>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12 col-md-12">
                                <label class="form-check-label">
                                    <input name="included[admin]" type="checkbox" 
                                    {{ 
                                        isset($module['included']['admin'])  
                                        && $module['included']['admin'] == 'on' 
                                        ? 'checked' 
                                        : '' 
                                    }}> <span class="checkbox-label">@lang('Admin CRUD')</span>
                                </label>
                                <label class="form-check-label">
                                    <input name="included[user]" type="checkbox"
                                    {{ 
                                        isset($module['included']['user'])  
                                        && $module['included']['user'] == 'on' 
                                        ? 'checked' 
                                        : '' 
                                    }}> <span class="checkbox-label">@lang('User CRUD')</span>
                                </label>
                            </div>

                            <div class="col-12 col-md-12 mt-3">
                                <label class="form-check-label">
                                    <input name="included[soft_deletes]" type="checkbox" 
                                    {{ 
                                        isset($module['included']['soft_deletes'])  
                                        && $module['included']['soft_deletes'] == 'on' 
                                        ? 'checked' 
                                        : '' 
                                    }}> <span class="checkbox-label">@lang('Soft Deletes')</span>
                                </label>
                                <label class="form-check-label">
                                    <input name="included[generate_api_crud]" type="checkbox" 
                                    {{ 
                                        isset($module['included']['generate_api_crud'])  
                                        && $module['included']['generate_api_crud'] == 'on' 
                                        ? 'checked' 
                                        : '' 
                                    }}> <span class="checkbox-label">@lang('Generate API CRUD')</span>
                                </label>
                            </div>
                            <div class="col-12 col-md-12 mt-3">
                                <label class="form-check-label">
                                    <input name="included[create_form]" type="checkbox" 
                                    {{ 
                                        isset($module['included']['create_form'])  
                                        && $module['included']['create_form'] == 'on' 
                                        ? 'checked' 
                                        : '' 
                                    }}> <span class="checkbox-label">@lang('Create form')</span>
                                </label>
                                <label class="form-check-label">
                                    <input name="included[edit_form]" type="checkbox" 
                                    {{ 
                                        isset($module['included']['edit_form'])  
                                        && $module['included']['edit_form'] == 'on' 
                                        ? 'checked' 
                                        : '' 
                                    }}> <span class="checkbox-label">@lang('Edit form')</span>
                                </label>
                                <label class="form-check-label">
                                    <input name="included[show_page]" type="checkbox" 
                                    {{ 
                                        isset($module['included']['show_page'])  
                                        && $module['included']['show_page'] == 'on' 
                                        ? 'checked' 
                                        : '' 
                                    }}> <span class="checkbox-label">@lang('Show page')</span>
                                </label>
                                <label class="form-check-label">
                                    <input name="included[delete_action]" type="checkbox" 
                                    {{ 
                                        isset($module['included']['delete_action'])  
                                        && $module['included']['delete_action'] == 'on' 
                                        ? 'checked' 
                                        : '' 
                                    }}> <span class="checkbox-label">@lang('Delete action')</span>
                                </label>
                                <label class="form-check-label">
                                    <input name="included[multi_delete_action]" type="checkbox" 
                                    {{ 
                                        isset($module['included']['multi_delete_action'])  
                                        && $module['included']['multi_delete_action'] == 'on' 
                                        ? 'checked' 
                                        : '' 
                                    }}> <span class="checkbox-label">@lang('Multi-delete action')</span>
                                </label>
                            </div>
                            @if(Module::has('Subscriptions'))
                            <div class="col-12 col-md-12 mt-4">
                                <h6>@lang('Accessibility')</h6>
                                <label class="form-check-label">
                                    <input name="included[only_subscribers]" type="checkbox"
                                    {{ 
                                        isset($module['included']['only_subscribers'])  
                                        && $module['included']['only_subscribers'] == 'on' 
                                        ? 'checked' 
                                        : '' 
                                    }}

                                    {{ 
                                        isset($module['included']['user'])  
                                        && $module['included']['user'] == 'on' 
                                        ? '' 
                                        : 'disabled' 
                                    }}> <span class="checkbox-label">@lang('Only subscribers')</span>
                                </label>
                            </div>
                            @endif
                        </div>

                    </div>

                </div>


                <div class="card card-form">
                    <div class="card-header">
                        <h4>@lang('Fields')</h4>
                    </div>
                
                    <div class="card-body">
                        
                        <div class="table-responsive" id="module-fields-datatable">
                            <input type="hidden" name="models" value="{{ $models }}">
                            <table class="table" id="fields-table">
                                <thead>
                                    <tr>
                                        <th class="display-none">&nbsp;</th>
                                        <th class="field-handle-head"><span>&nbsp;</span></th>
                                        <th width="1%">@lang('Field Type')</th>
                                        <th>@lang('Database Column')</th>
                                        <th>@lang('Visual Title')</th>
                                        <th>@lang('In List')</th>
                                        <th>@lang('In Create')</th>
                                        <th>@lang('In Edit')</th>
                                        <th>@lang('In Show') </th>
                                        <th>@lang('Is Sortable') </th>
                                        <th>@lang('Required')</th>
                                        <th>@lang('Edit')</th>
                                        <th>@lang('Delete')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @include('modules::admin.partials.edit-fields', ['show_fields' => ['id']])
                                </tbody>
                                <tbody id="fields">
                                    @include('modules::admin.partials.edit-fields', ['unshow_fields' => ['id', 'created_at', 'updated_at', 'deleted_at']])
                                </tbody>
                                <tbody>
                                    @include('modules::admin.partials.edit-fields', ['show_fields' => ['created_at', 'updated_at', 'deleted_at']])
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#field-settings-modal" data-backdrop="static" data-keyboard="false"><i class="fas fa-plus"></i> @lang('Add new field')</button>

                    </div>

                </div>


                <div class="card card-form">
                    <div class="card-header">
                        <h4>@lang('Table')</h4>
                    </div>
                
                    <div class="card-body mb-0">
                        <div class="row">
                            <div class="col-3 col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><b>@lang('Entries per page')</b></span>
                                        </div>
                                        <select name="entries_per_page" class="form-control custom-select">
                                            <option value="10" {{ $module['entries_per_page'] == 10 ? 'selected' : '' }}>@lang('10 entries')</option>
                                            <option value="25" {{ $module['entries_per_page'] == 25 ? 'selected' : '' }}>@lang('25 entries')</option>
                                            <option value="50" {{ $module['entries_per_page'] == 50 ? 'selected' : '' }}>@lang('50 entries')</option>
                                            <option value="100" {{ $module['entries_per_page'] == 100 ? 'selected' : '' }}>@lang('100 entries')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-3 col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><b>@lang('Order by')</b></span>
                                        </div>
                                        <select name="order_by_column" class="form-control custom-select">
                                            @foreach($module['fields'] as $field => $attributes)
                                                @php
                                                    $fieldAtts = json_decode($attributes, true);
                                                    $excluded = ['created_at', 'updated_at', 'deleted_at'];
                                                @endphp

                                                @if(!in_array($field, $excluded))
                                                    <option value="{{$field}}" {{ $module['order_by_column'] == $field ? 'selected' : '' }}>{{ ModuleHelper::getValueByKey($fieldAtts, 'field_visual_title') }}</option>
                                                @endif
                                                
                                            @endforeach
                                            
                                        </select>
                                        <select name="order_by_value" class="form-control custom-select">
                                            <option value="desc" {{ $module['order_by_value'] == 'desc' ? 'selected' : '' }}>@lang('DESC')</option>
                                            <option value="asc" {{ $module['order_by_value'] == 'asc' ? 'selected' : '' }}>@lang('ASC')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                <a href="{{ route('admin.modules.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>  
            </form>
        </div>
    </div>
    

@endsection

@section('modals')
    <div class="modal fade" role="dialog" id="field-settings-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Field Settings')</h5>
                </div>
                <form id="field-settings-form" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-3 col-md-3">
                                <div class="form-group">
                                    <label for="name">@lang('Type')</label>
                                    
                                    <select name="field_type" id="type" class="form-control custom-select">
                                        <optgroup label="@lang('Text Fields')">
                                            <option value="text">@lang('Text')</option>
                                            <option value="email">@lang('Email')</option>
                                            <option value="textarea">@lang('Textarea')</option>
                                            <option value="password">@lang('Password')</option>
                                        </optgroup>
                                        <optgroup label="@lang('Choice Fields')">
                                            <option value="radio">@lang('Radio')</option>
                                            <option value="select">@lang('Select')</option>
                                            <option value="checkbox">@lang('Checkbox')</option>
                                        </optgroup>
                                        <optgroup label="@lang('Number Fields')">
                                            <option value="number">@lang('Integer')</option>
                                            <option value="float">@lang('Float')</option>
                                            <option value="money">@lang('Money')</option>
                                        </optgroup>
                                        <optgroup label="@lang('Date/Time Fields')">
                                            <option value="date">@lang('Date Picker')</option>
                                            <option value="datetime">@lang('Date/Time Picker')</option>
                                            <option value="time">@lang('Time Picker')</option>
                                        </optgroup>
                                        <optgroup label="@lang('File Upload Fields')">
                                            <option value="file">@lang('File')</option>
                                            <option value="photo">@lang('Photo')</option>
                                        </optgroup>
                                        <optgroup label="@lang('Relationship Fields')">
                                            <option value="belongsToRelationship">@lang('BelongsTo Relationship')</option>
                                            <option value="belongsToManyRelationship">@lang('BelongsToMany Relationship')</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3 col-md-3">
                                <div class="form-group">
                                    <label for="name">@lang('Label')</label>
                                    <input type="text" class="form-control" name="field_visual_title" placeholder="@lang('Visual title')" required data-validation="required|@lang('The label field is required.')">
                                </div>
                            </div>

                            <div class="col-3 col-md-3">
                                <div class="form-group">
                                    <label for="name">@lang('Database column')</label>
                                    <input type="text" class="form-control" name="field_database_column" placeholder="@lang('Database column')" required data-validation="required|@lang('The database column field is required.'),unique|@lang('The database column field must be unique.')">
                                </div>
                            </div>

                            <div class="col-3 col-md-3">
                                <div class="form-group">
                                    <label for="name">@lang('Validation Options')</label>
                                    <select class="form-control custom-select" name="field_validation">
                                        <option value="optional">@lang('Optional')</option>
                                        <option value="required">@lang('Required')</option>
                                        <option value="required_unique">@lang('Required | Unique')</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!-- End .row -->


                        <div class="row">
                            <div class="col-6 col-md-6">
                                <div class="form-group">
                                    <label for="name">@lang('Tooltip text (will be visible below the input)')</label>
                                    <input type="text" class="form-control" name="field_tooltip" placeholder="@lang('Tooltip text')">
                                </div>
                            </div>

                            <div class="col-6 col-md-6">
                                <div class="form-group">
                                    <label for="name">@lang('Options')</label>
                                    <div class="col-12 col-md-12 mt-3">
                                        <label class="form-check-label">
                                            <input name="in_list" type="checkbox" checked> <span class="checkbox-label">@lang('In list')</span>
                                        </label>
                                        <label class="form-check-label">
                                            <input name="in_create" type="checkbox" checked> <span class="checkbox-label">@lang('In create')</span>
                                        </label>
                                        <label class="form-check-label">
                                            <input name="in_edit" type="checkbox" checked> <span class="checkbox-label">@lang('In edit')</span>
                                        </label>
                                        <label class="form-check-label">
                                            <input name="in_show" type="checkbox" checked> <span class="checkbox-label">@lang('In show')</span>
                                        </label>
                                        <label class="form-check-label">
                                            <input name="is_sortable" type="checkbox" checked> <span class="checkbox-label">@lang('Is sortable')</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End .row -->

                        <hr id="field-extra-options-separator" />

                        <div id="field-extra-options">
                            <div id="field-extra-options">
                                <h4>@lang('Extra options')</h4>
                                <div class="row">
                                    <div class="col-3 col-md-3">
                                        <div class="form-group">
                                            <label for="min_length">@lang('Min length')</label>
                                            <input type="number" class="form-control" name="min_length" placeholder="@lang('Min length')">
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <div class="form-group">
                                            <label for="max_length">@lang('Max length')</label>
                                            <input type="number" class="form-control" name="max_length" placeholder="@lang('Max length')"></div>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-3 col-md-3"><div class="form-group mb-0">
                                        <label for="default_value">@lang('Default value')</label>
                                        <input type="text" class="form-control" name="default_value" placeholder="@lang('Default value')"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        

                    
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                        <button type="button" class="btn btn-primary" id="field-settings-button">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection