<div class="modal fade" tabindex="-1" role="dialog" id="add-coupon-user-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add applicable users')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="users">@lang('Select users')</label>
                    <select id="users" placeholder="@lang('Pick a user')..." multiple></select>
                </div>

                <div class="modal-footer bg-whitesmoke br">
                    <button
                        type="button"
                        class="btn btn-primary btn-create-coupon-user"
                        data-href="{{ route('admin.coupons.promo-codes.user-store', [$promoCode]) }}"
                    >@lang('Save')</button>
                </div>
            </div>
        </div>
    </div>
</div>
