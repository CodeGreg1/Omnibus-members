<div class="modal fade" tabindex="-1" role="dialog" id="add-coupon-package-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add applicable packages')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="plans">@lang('Select packages')</label>
                    <select id="plans" placeholder="@lang('Pick a plan')..." multiple></select>
                </div>

                <div class="modal-footer bg-whitesmoke br">
                    <button
                        type="button"
                        class="btn btn-primary btn-create-coupon-package"
                        data-href="{{ route('admin.subscriptions.coupons.packages.store', $coupon) }}"
                    >@lang('Save')</button>
                </div>
            </div>
        </div>
    </div>
</div>
