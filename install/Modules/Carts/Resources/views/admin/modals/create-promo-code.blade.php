<div class="modal fade" tabindex="-1" role="dialog" id="create-coupon-promo-code-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add a new promo code')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createCouponPromoCodeForm">
                    <div class="form-group">
                        <label for="code">@lang('Code')</label>
                        <input type="text" class="form-control" name="code" placeholder="PURCHASE001">
                    </div>
                    <div class="form-group">
                        <label for="max_redemptions">@lang('Max redemptions')</label>
                        <input type="bumber" class="form-control" name="max_redemptions">
                    </div>

                    <div class="form-group">
                        <label for="users">
                            @lang('Users')
                            <span class="text-muted"> (<small>@lang('Optional')</small>)</span>
                        </label>
                        <select id="users" placeholder="@lang('Pick a user')..." multiple></select>
                        <small class="form-text text-muted form-help">
                            @lang('Set specific users that can use this promo code')
                        </small>
                    </div>

                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-primary" id="btn-create-coupon-promo-code" data-id="{{ $coupon->id }}">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
