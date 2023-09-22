<button id="2fa-download-recovery-codes-button">Trigger Modal</button>
<div class="modal fade" tabindex="-1" role="dialog" id="2fa-download-recovery-codes-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Authy Verification')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <p>@lang('Download :authy authenticator then register your mobile number and input token generated from Authy to verify your two factor authentication.', ['authy' => '<a href="https://authy.com/" target="_blank">Authy</a>'])</p>

                <form id="profile-verify-two-factor-form" method="post">
                    <div class="row 2fa-content">
                        <div class="col-12">
                            <div class="row 2fa-content">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="token">@lang('Token')</label>
                                        <input type="text"
                                               class="form-control"
                                               id="token"
                                               placeholder="@lang('Token')"
                                               name="token">
                                    </div>
                                </div>
                            </div> 

                            <button class="btn btn-primary btn-block" type="submit">@lang('Verify')</button>
                        </div>
                    </div>
                </form>
                
                
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