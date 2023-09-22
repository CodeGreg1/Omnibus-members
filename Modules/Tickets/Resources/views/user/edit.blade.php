@extends('tickets::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/tinymce/js/tinymce.min.js') }}"></script>
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('user.tickets.index') }}"> @lang('Tickets')</a>
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
                
                <form id="user-tickets-edit-form" method="post" data-action="{{ route('user.tickets.update', $tickets->id) }}">
                    @method('patch')
                    <div class="card-body">
                        <div class="row">
                            
                            
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="number">@lang('Number') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="number" 
                                        value="{{ $tickets->number }}"
                                        name="number"
                                        class="form-control"
                                        step="1" required>
                                </div>
                            </div>


                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="user_id">@lang('User') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <select name="user_id" class="form-control select2">
                                        <option value="">@lang('Select user')</option>
                                        @foreach($users as $id => $entry)
                                            <option value="{{ $id }}" {{ $tickets->user_id == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="category_id">@lang('Category') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <select name="category_id" class="form-control select2">
                                        <option value="">@lang('Select category')</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $id }}" {{ $tickets->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="subject">@lang('Subject') <span class="text-muted">(@lang('Required'))</span></label>
                                    <input type="text" 
                                        value="{{ $tickets->subject }}"
                                        name="subject"
                                        class="form-control" required>
                                </div>
                            </div>


                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Priority') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="priority" class="form-control select2">
                                        <option value="">@lang('Select priority')</option>
                                        @foreach(Modules\Tickets\Models\Ticket::PRIORITY_SELECT as $key => $label)
                                            <option value="{{ $key }}" {{ $tickets->priority == $label ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="message">@lang('Message') <span class="text-muted">(@lang('Required'))</span></label>
                                    <textarea name="message"
                                        id="message"
                                        class="form-control user-tickets-tinymce-default">{{ $tickets->message }}</textarea>
                                </div>
                            </div>


                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label for="attachments">@lang('Attachments') <span class="text-muted">(@lang('Optional'))</span></label>
                                    <div class="dropzone dropzone-multiple" data-toggle="dropzone1" data-dropzone-url="http://" id="user-tickets-attachments-dropzone" 
                                    data-field-name="attachments"
                                    data-dropzone-multiple>
                                        <div class="fallback">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="dropzone-1" name="attachments" multiple>
                                                <label class="custom-file-label" for="customFileUpload">{{__('Choose file')}}</label>
                                            </div>
                                        </div>
                                        <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="avatar">
                                                            <img class="rounded" src="{{ url('upload/media/default/dr-default.png') }}" alt="Image placeholder" data-dz-thumbnail>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="text-sm mb-1" data-dz-name>...</h6>
                                                        <p class="small text-muted mb-0" data-dz-size></p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#" class="dropdown-item" data-dz-remove>
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <ul class="tickets-attachments-dropzone-uploaded dropzone-uploaded list-group list-group-lg list-group-flush">

                                        @foreach($tickets->attachments as $entry)
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <div class="avatar">
                                                            <img class="rounded" src="{!! $entry->thumbnail !!}" alt="{!! $entry->file_name !!}">
                                                        </div>
                                                    </div>
                                                    <div class="col ml-n2">
                                                        <h6 class="text-sm mb-1" title="{!! $entry->file_name !!}">{!! $entry->file_name !!}</h6>
                                                        <p class="small text-muted mb-0">{!! $entry->human_readable_size !!}</p>
                                                    </div>
                                                    <div class="col-auto download-col">
                                                        <a href="{{ $entry->url }}" class="action-item" data-id="{{ $entry->uuid }}" title="{{ __('Download') }}" download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="javascript:void(0)" 
                                                        class="remove-dropzone-file action-item delete-item" data-dz-id="#user-tickets-attachments-dropzone" data-id="{{ $entry->uuid }}"
                                                        data-route="{{ route('user.tickets.remove-media') }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                        
                                    </ul>
                                    
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>@lang('Status') <span class="text-muted">(@lang('Required'))</span></label>
                                    <select name="status" class="form-control select2">
                                        <option value="">@lang('Select status')</option>
                                        @foreach(Modules\Tickets\Models\Ticket::STATUS_SELECT as $key => $label)
                                            <option value="{{ $key }}" {{ $tickets->status == $label ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save changes')</button>
                        <a href="{{ route('user.tickets.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>   
                </form>    



            </div>
        </div>
    </div>
    

@endsection
