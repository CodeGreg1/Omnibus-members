<div class="tab-pane fade" id="paypal-gateway-deposit" role="tabpanel" aria-labelledby="paypal-gateway-deposit-tab">
    <form id="paypal-gatway-deposit-settings-form" data-route="{{ route('admin.settings.payment-gateways.deposit-settings.update') }}">
        <h6>@lang('Deposit Settings')</h6>
        <div class="mt-3 row">
            <div class="col-12">
                <div class="form-group">
                    <label for="paypal_deposit_currency">@lang('Currency') <span class="text-muted">(@lang('Required'))</span></label>
                    <input type="text" class="form-control" readonly value="{{ setting('currency', config('cashier.currency')) }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="paypal_deposit_min_limit">@lang('Min limit')</label>
                    <input type="text" name="paypal_deposit_min_limit" class="form-control" value="{{ setting('paypal_deposit_min_limit') }}" data-decimals="2">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="paypal_deposit_max_limit">@lang('Max limit')</label>
                    <input type="text" name="paypal_deposit_max_limit" class="form-control" value="{{ setting('paypal_deposit_max_limit') }}" data-decimals="2">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="d-block" for="paypal_deposit_fixed_charge">@lang('Fixed charge')</label>
                    <input type="text" class="form-control" name="paypal_deposit_fixed_charge" value="{{ setting('paypal_deposit_fixed_charge') }}" data-decimals="2">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group mb-0">
                    <label class="d-block" for="paypal_deposit_percent_charge">@lang('Percent Charge')</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="paypal_deposit_percent_charge" name="paypal_deposit_percent_charge" value="{{ setting('paypal_deposit_percent_charge') }}" data-decimals="2">
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
