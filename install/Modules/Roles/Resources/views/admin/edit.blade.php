@extends('roles::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="#"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.roles.index') }}"> @lang('Roles')</a>
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
                
                <form id="roles-edit-form" method="post" data-action="{{ route('admin.roles.update', $role->id) }}">
                    @method('patch')
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="type"
                                        value="{{ $role->name }}" 
                                        name="name"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="display_name">@lang('Display Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="type" 
                                        value="{{ $role->display_name }}" 
                                        name="display_name"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="type">@lang('Role Type') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="type"
                                        class="form-control">
                                        <option value="">@lang('Select Type')</option>
                                        @foreach($roleTypes as $roleType)
                                            <option value="{{ $roleType }}" {{ $role->type == $roleType ? 'selected' : '' }}>{{ $roleType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="description">@lang('Description') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <input type="type" 
                                        value="{{ $role->description }}" 
                                        name="description"
                                        class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-12">
                                <h6>@lang('Assign Permissions')</h6>

                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" id="table-role-permissions">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="permission-section-column">Module</th>
                                            <th scope="col" class="permission-select-all-column">
                                                <div class="form-check form-check-inline">
                                                    <input 
                                                        class="form-check-input select-all-permissions" 
                                                        type="checkbox" 
                                                        id="select-all-global"
                                                        {{
                                                            isset($rolePermissions)
                                                            ? 
                                                                count($permissions) == count($rolePermissions) 
                                                                ? 'checked'
                                                                : '' 
                                                            : ''
                                                        }}>
                                                    <label class="form-check-label" for="select-all-global">Select All</label>
                                                </div>
                                            </th>
                                          <th scope="col">Available Permissions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($permissions as $module=>$permissionArray)
                                                <tr>
                                                    <th scope="row">{{ ucfirst(str_replace('-', ' ', $module)) }}</th>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input 
                                                                class="form-check-input select-permission select-all-module-permissions select-all-{{str_replace(' ', '-', strtolower($module))}}-permissions" 
                                                                data-class="select-all-{{str_replace(' ', '-', strtolower($module))}}-permissions"
                                                                type="checkbox" 
                                                                id="select-all-{{str_replace(' ', '-', strtolower($module))}}" 
                                                                {{ 
                                                                    isset($rolePermissions[$module])
                                                                    ? 
                                                                        count($permissionArray['permissions']) == count($rolePermissions[$module]) 
                                                                        ? 'checked'
                                                                        : '' 
                                                                    : ''
                                                                }}>
                                                            <label class="form-check-label" for="select-all-{{str_replace(' ', '-', strtolower($module))}}">Select All</label>
                                                        </div>
                                                    </td>
                                                    <td class="pt-2 pb-2">
                                                        @php
                                                            $chunkedPermissions = array_chunk($permissionArray['permissions'], 2);
                                                        @endphp

                                                        <table class="table table-striped">
                                                            <tbody>
                                                                @foreach($chunkedPermissions as $chunkedPermission)
                                                                    <tr>
                                                                        @foreach($chunkedPermission as $permission)
                                                                            <td width="33.33%">
                                                                                <div class="form-check form-check-inline">
                                                                                    <input 
                                                                                        class="form-check-input select-permission select-module-permission select-{{str_replace(' ', '-', strtolower($module))}}-permission" 
                                                                                        data-class="select-{{str_replace(' ', '-', strtolower($module))}}-permission" 
                                                                                        type="checkbox" 
                                                                                        id="{{$permission['name']}}-permission" 
                                                                                        name="permissions[{{ $permission['name'] }}]"
                                                                                        value="{{ $permission['name'] }}"
                                                                                        {{ 
                                                                                        isset($rolePermissions[$module])
                                                                                        ?
                                                                                            in_array($permission['name'], $rolePermissions[$module]) 
                                                                                            ? 'checked'
                                                                                            : '' 
                                                                                        : ''
                                                                                        }}>
                                                                                    <label class="form-check-label" for="{{$permission['name']}}-permission">
                                                                                        {{ $permission['name'] }}
                                                                                    </label>
                                                                                </div>
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            
                                                            
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>    



            </div>
        </div>
    </div>
    

@endsection
