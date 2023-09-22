@extends('menus::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/nestable2/css/jquery.nestable.min.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/nestable2/js/jquery.nestable.min.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.menus.index') }}"> {!! config('menus.name') !!}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">

            <div class="card card-form" id="details">
                <div class="card-header">
                    <h4>{{ $pageTitle }}</h4>
                </div>

                <form id="menu-create-form" method="post" data-action="{{ route('admin.menus.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input name="name" 
                                        class="form-control" 
                                        placeholder="e.g. Sidebar menu" 
                                        type="text">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Type') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="type" class="form-control select2">
                                        <option value="">@lang('Select type')</option>
                                        @foreach($menuTypes as $menuType)
                                            <option>{{ $menuType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-12">

                                <ul class="nav nav-tabs" id="language-tab" role="tablist">
                                    @foreach($languages as $language)
                                        <li class="nav-item">
                                            <a class="nav-link {{ $language->code == 'en' ?'active':'' }}" id="{{$language->code}}-tab" data-toggle="tab" href="#{{$language->code}}" role="tab" aria-controls="{{$language->code}}" aria-selected="true">{{ $language->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content tab-bordered" id="language-content">
                                    @foreach($languages as $language)
                                        <div class="tab-pane fade {{ $language->code == 'en' ?'show active':'' }}" id="{{$language->code}}" role="tabpanel" aria-labelledby="{{$language->code}}-tab">
                                                
                                            <label>@lang('Menu items') </label>
                                            <input type="hidden" name="menu-create-output[{{$language->code}}]" id="menu-create-output-{{$language->code}}" class="menu-create-output form-control">
                                            <div class="custom-dd-empty dd dd-full-width create-menu-item" id="create-menu-item-{{$language->code}}" data-lang="{{$language->code}}">
                                                <ol class="dd-list">

                                                </ol>
                                            </div>

                                            <div class="add-menu-item"><i class="fas fa-plus"></i> @lang('Add menu item')</div>
                                        </div> 
                                    @endforeach
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button type="submit" class="btn btn-primary btn-lg float-right">@lang('Save changes')</button>
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>            
            </div>

        </div> 

    </div>
@endsection

@section('modals')
    <div class="modal fade" role="dialog" id="add-menu-item-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add menu item')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="menu-item-create-form" method="post" autocomplete='off'>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Name') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input name="name" 
                                        class="form-control" 
                                        placeholder="@lang('e.g. About us')" 
                                        type="text"
                                        required>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Type') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="link_type" class="form-control select2">
                                        @foreach($menuLinkTypes as $menuLinkType)
                                            <option {{ $menuLinkType === 'Default' ? 'selected':'' }}>{{ $menuLinkType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 menu-link-route-type">
                                <div class="form-group">
                                    <label for="link">@lang('Link') <span class="text-muted">(@lang('Required'))</span></label>

                                    <select name="link"
                                        class="form-control select2"
                                        id="menu-links"
                                        required>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 menu-link-url-type d-none">
                                <div class="form-group">
                                    <label for="url">@lang('Full url') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input name="url"
                                        class="form-control"
                                        placeholder="@lang('https://example.com')"
                                        type="text">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="icon">@lang('Icon') <span class="text-muted">(@lang('Required'))</span></label>

                                    <select name="icon" 
                                        class="form-control icon-select2"
                                        required>
                                        <option value="">@lang('Select Icon')</option>
                                        @foreach(fontawesome_all() as $fa)
                                            <option value="{{ $fa }}">{{ $fa }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="name">@lang('Class') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <input name="class" 
                                        class="form-control" 
                                        placeholder="@lang('e.g. class1 class2')" 
                                        type="text">
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="target" value="1">
                                        <label class="form-check-label" for="target">@lang('Open link in a new tab')</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group mb-0">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="status" value="1" checked="checked">
                                        <label class="form-check-label" for="status">@lang('Show on menu')</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary" id="menu-item-create-button">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection