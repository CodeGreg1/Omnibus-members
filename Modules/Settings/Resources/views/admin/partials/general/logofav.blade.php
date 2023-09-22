<div class="card mb-0"> 
                            
    <div class="card-header">
        <h4>@lang('Default Profile Photo, Logo, & Favicon')</h4>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="default_profile_photo">@lang('Default Profile Photo') <span class="text-muted">(@lang('Required'))</span></label>
                    <div class="dropzone dropzone-multiple" data-toggle="dropzone1" data-dropzone-url="http://" id="settings-default_profile_photo-dropzone" 
                    data-field-name="default_profile_photo"
                    data-dropzone-multiple>
                        <div class="fallback">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="dropzone-1" name="default_profile_photo" multiple>
                                <label class="custom-file-label" for="customFileUpload">{{__('Choose file')}}</label>
                            </div>
                        </div>
                        <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div>
                                            <img class="rounded" src="{{ url('upload/media/default/dr-default.png') }}" alt="Image placeholder" data-dz-thumbnail>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-sm mb-1">&nbsp;</h6>
                                        <p class="small text-muted mb-0">&nbsp;</p>
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

                    @if(setting('default_profile_photo'))
                    <ul class="settings-default_profile_photo-dropzone-uploaded dropzone-uploaded list-group list-group-lg list-group-flush">

                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div>
                                        <img class="rounded" src="{!! setting('default_profile_photo') !!}" alt="Default Profile Photo" width="20%">
                                    </div>
                                </div>
                                <div class="col ml-n2">
                                    <h6 class="text-sm mb-1">&nbsp;</h6>
                                    <!-- <p class="small text-muted mb-0">&nbsp;</p> -->
                                </div>
                                <div class="col-auto">
                                    <a href="javascript:void(0)" 
                                    class="settings-remove-dropzone-file action-item delete-item" data-dz-id="#settings-default_profile_photo-dropzone">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        
                    </ul>
                    @endif
                    
                    <small class='form-text text-muted form-help'>@lang('Recommended and max size is 500x500.')</small>
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="colored_logo">@lang('Colored Logo') <span class="text-muted">(@lang('Required'))</span></label>
                    <div class="dropzone dropzone-multiple" data-toggle="dropzone1" data-dropzone-url="http://" id="settings-colored_logo-dropzone" 
                    data-field-name="colored_logo"
                    data-dropzone-multiple>
                        <div class="fallback">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="dropzone-1" name="colored_logo" multiple>
                                <label class="custom-file-label" for="customFileUpload">{{__('Choose file')}}</label>
                            </div>
                        </div>
                        <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div>
                                            <img class="rounded" src="{{ url('upload/media/default/dr-default.png') }}" alt="Image placeholder" data-dz-thumbnail>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-sm mb-1">&nbsp;</h6>
                                        <p class="small text-muted mb-0">&nbsp;</p>
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

                    @if(setting('colored_logo'))
                    <ul class="settings-colored_logo-dropzone-uploaded dropzone-uploaded list-group list-group-lg list-group-flush">

                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div>
                                        <img class="rounded" src="{!! setting('colored_logo') !!}" alt="Primary Logo">
                                    </div>
                                </div>
                                <div class="col ml-n2">
                                    <h6 class="text-sm mb-1">&nbsp;</h6>
                                    <!-- <p class="small text-muted mb-0">&nbsp;</p> -->
                                </div>
                                <div class="col-auto">
                                    <a href="javascript:void(0)" 
                                    class="settings-remove-dropzone-file action-item delete-item" data-dz-id="#settings-colored_logo-dropzone">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        
                    </ul>
                    @endif
                    
                    <small class='form-text text-muted form-help'>@lang('Recommended and max size is 240x70.')</small>
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group">
                    <label for="white_logo">@lang('White Logo') <span class="text-muted">(@lang('Required'))</span></label>
                    <div class="dropzone dropzone-multiple" data-toggle="dropzone1" data-dropzone-url="http://" id="settings-white_logo-dropzone" 
                    data-field-name="white_logo"
                    data-dropzone-multiple>
                        <div class="fallback">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="dropzone-1" name="white_logo" multiple>
                                <label class="custom-file-label" for="customFileUpload">{{__('Choose file')}}</label>
                            </div>
                        </div>
                        <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div>
                                            <img class="rounded" src="{{ url('upload/media/default/dr-default.png') }}" alt="Image placeholder" data-dz-thumbnail>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-sm mb-1">&nbsp;</h6>
                                        <p class="small text-muted mb-0">&nbsp;</p>
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

                    @if(setting('white_logo'))
                    <ul class="settings-white_logo-dropzone-uploaded dropzone-uploaded list-group list-group-lg list-group-flush">

                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div>
                                        <img class="rounded" src="{!! setting('white_logo') !!}" alt="Primary Logo">
                                    </div>
                                </div>
                                <div class="col ml-n2">
                                    <h6 class="text-sm mb-1">&nbsp;</h6>
                                    <!-- <p class="small text-muted mb-0">&nbsp;</p> -->
                                </div>
                                <div class="col-auto">
                                    <a href="javascript:void(0)" 
                                    class="settings-remove-dropzone-file action-item delete-item" data-dz-id="#settings-white_logo-dropzone">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        
                    </ul>
                    @endif
                    
                    <small class='form-text text-muted form-help'>@lang('Recommended and max size is 240x70.')</small>
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group mb-0">
                    <label for="favicon">@lang('Favicon') <span class="text-muted">(@lang('Required'))</span></label>
                    <div class="dropzone dropzone-multiple" data-toggle="dropzone1" data-dropzone-url="http://" id="settings-favicon-dropzone" 
                    data-field-name="favicon"
                    data-dropzone-multiple>
                        <div class="fallback">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="dropzone-1" name="colored_logo" multiple>
                                <label class="custom-file-label" for="customFileUpload">{{__('Choose file')}}</label>
                            </div>
                        </div>
                        <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div>
                                            <img class="rounded setting-file-thumbnail" src="{{ url('upload/media/default/dr-default.png') }}" alt="Image placeholder" data-dz-thumbnail>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-sm mb-1">&nbsp;</h6>
                                        <p class="small text-muted mb-0">&nbsp;</p>
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

                    @if(setting('favicon'))
                    <ul class="settings-favicon-dropzone-uploaded dropzone-uploaded list-group list-group-lg list-group-flush">

                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div>
                                        <img class="rounded setting-file-thumbnail" src="{!! setting('favicon') !!}" alt="Favicon">
                                    </div>
                                </div>
                                <div class="col ml-n2">
                                    <h6 class="text-sm mb-1">&nbsp;</h6>
                                    <!-- <p class="small text-muted mb-0">&nbsp;</p> -->
                                </div>
                                <div class="col-auto">
                                    <a href="javascript:void(0)" 
                                    class="settings-remove-dropzone-file action-item delete-item" data-dz-id="#settings-favicon-dropzone">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        
                    </ul>
                    @endif
                    
                    <small class='form-text text-muted form-help'>@lang('Recommended and max size is 32x32.')</small>
                </div>
            </div>

        </div>


        
    </div>

</div>