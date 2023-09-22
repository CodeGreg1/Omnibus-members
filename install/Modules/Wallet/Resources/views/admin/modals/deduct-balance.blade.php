<div class="modal fade" tabindex="-1" role="dialog" id="user-deduct-balance-admin-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Deduct Money')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="user-deduct-balance-admin-form">
                    <input type="hidden" name="user" value="{{ $user->id }}">
                    <input type="hidden" name="currency">

                    <div class="form-group">
                        <label class="d-block" for="user">@lang('User') <span class="text-muted">(@lang('Required'))</span></label>
                        <input type="text" class="form-control" readonly value="{{ $user->email }}">
                    </div>

                    <div class="form-group">
                        <label class="d-block" for="user">@lang('Current Balance')</label>
                        <h5 class="deduct-wallet-balance-label">$0.00</h5>
                    </div>

                    <div class="form-group">
                        <label class="d-block" for="amount">@lang('Amount') <span class="text-muted">(@lang('Required'))</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text deduct-wallet-currency-label">USD</div>
                            </div>
                            <input type="text" name="amount" data-decimals="2" class="form-control" id="inlineFormInputGroup" placeholder="0.00">
                        </div>
                    </div>

                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">@lang('Continue')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
