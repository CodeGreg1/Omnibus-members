<div class="convo-reply-form">
    <form id="ticket-reply-form" method="post" enctype="multipart/form-data" data-type="{{ $tickets->user_id == auth()->id() ? 'user' : 'admin' }}">
        <input type="hidden" name="ticket_id" value="{{ encrypt($tickets->id) }}">
        
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="message">@lang('Message') <span class="text-muted">(@lang('Required'))</span></label>
                    <textarea name="message"
                        id="message"
                        class="form-control ticket-reply-tinymce-default"></textarea>
                </div>
            </div>  
            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="attachments">@lang('Attachments') <span class="text-muted">(@lang('Optional'))</span></label>
                    <div class="dropzone dropzone-multiple" data-toggle="dropzone1" data-dropzone-url="http://" id="ticket-reply-attachments-dropzone" 
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
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Reply')</button>
            <button class="btn btn-white btn-lg float-right mr-3" id="btn-close-reply-form" type="button">@lang('Cancel')</button>
        </div> 
    </form>   
</div>