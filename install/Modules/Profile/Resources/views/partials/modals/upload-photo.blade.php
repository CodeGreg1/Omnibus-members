<div class="modal fade" tabindex="-1" role="dialog" id="general-profile-upload-photo">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Update Profile Picture')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                

                <form id="frm-upload-photo">
                    <input type="file" id="file-upload-photo" accept=".jpg,.jpeg,.png">
                    <button class="btn btn-primary btn-block" id="btn-trigger-upload-photo" type="button"> <i class="fas fa-plus"></i> @lang('Upload photo')</button>
                </form>

                <div class="mt-4">
                    <div class="avatar-wrapper">
                        <div id="avatar"></div>
                    </div>
                </div>

                <div class="avatar-controls mt-50">
                    <div class="row d-flex">
                        <div class="col-md-6">
                            <div id="btn-cancel-upload" class="btn btn-block btn-outline-secondary text-center">
                                <i class="fa fa-times"></i> @lang('Cancel')                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" id="btn-upload-photo" class="btn btn-primary btn-block text-center">
                                <i class="fa fa-check"></i> @lang('Save')                
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>