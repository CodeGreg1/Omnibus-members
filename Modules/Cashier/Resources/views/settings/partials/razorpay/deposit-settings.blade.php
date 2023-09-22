<div class="tab-pane fade" id="razorpay-gateway-deposit" role="tabpanel" aria-labelledby="razorpay-gateway-deposit-tab">
    <form id="razorpay-gateway-deposit-settings-form" data-route="{{ route('admin.settings.payment-gateways.deposit-settings.update') }}">
        <h6>@lang('Deposit Settings')</h6>
        <div class="mt-3 row">
            <div class="col-12">
                <div class="form-group">
                    <label for="razorpay_deposit_currency">@lang('Currency') <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" class="form-control" readonly value="{{ setting('currency', config('cashier.currency')) }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="razorpay_deposit_min_limit">@lang('Min limit')</label>
                    <input type="text" name="razorpay_deposit_min_limit" class="form-control" value="{{ setting('razorpay_deposit_min_limit') }}" data-decimals="2">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="razorpay_deposit_max_limit">@lang('Max limit')</label>
                    <input type="text" name="razorpay_deposit_max_limit" class="form-control" value="{{ setting('razorpay_deposit_max_limit') }}" data-decimals="2">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="d-block" for="razorpay_deposit_fixed_charge">@lang('Fixed charge')</label>
                    <input type="text" class="form-control" name="razorpay_deposit_fixed_charge" value="{{ setting('razorpay_deposit_fixed_charge') }}" data-decimals="2">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label class="d-block" for="razorpay_deposit_percent_charge">@lang('Percent Charge')</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="razorpay_deposit_percent_charge" name="razorpay_deposit_percent_charge" value="{{ setting('razorpay_deposit_percent_charge') }}" data-decimals="2">
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
