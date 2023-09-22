<div class="modal fade" tabindex="-1" role="dialog" id="wallet-confirm-otp-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirm action')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>@lang('Please check your mailbox for your one-time password (OTP).')</p>
                <form id="wallet-confirm-otp-form">
                    <div class="form-group">
                        <label class="d-block" for="otp">@lang('One-Time Password(OTP)') <span class="text-muted">(@lang('Required'))</span></label>
                        <input type="text" name="otp" class="form-control form-control-lg">
                    </div>

                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-success mr-2" id="btn-resend-otp">@lang('Resend')</button>
                        <button type="button" class="btn btn-primary" id="btn-confirm-otp">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
