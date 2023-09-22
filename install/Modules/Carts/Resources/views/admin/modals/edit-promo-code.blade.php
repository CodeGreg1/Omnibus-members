<div class="modal fade" tabindex="-1" role="dialog" id="edit-coupon-promo-code-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Edit promo code')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateCouponPromoCodeForm">
                    <input type="hidden" class="form-control" name="id" value="{{ $promoCode->id }}">
                    <div class="form-group">
                        <label for="code">@lang('Code')</label>
                        <input type="text" class="form-control" name="code" placeholder="PURCHASE001" value="{{ $promoCode->code }}">
                    </div>
                    <div class="form-group">
                        <label for="max_redemptions">@lang('Max redemptions')</label>
                        <input type="bumber" class="form-control" name="max_redemptions" value="{{ $promoCode->max_redemptions ? $promoCode->max_redemptions : '' }}">
                    </div>
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" @checked($promoCode->active)>
                        <label class="custom-control-label" for="active">@lang('Status')</label>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-primary" id="btn-update-coupon-promo-code" data-route="{{ route('admin.coupons.promo-codes.update', $promoCode) }}">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
