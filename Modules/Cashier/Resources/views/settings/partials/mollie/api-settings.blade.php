<div class="tab-pane fade" id="mollie-gateway-api" role="tabpanel" aria-labelledby="mollie-gateway-api-tab">
    <form id="mollie-gateway-api-settings-form" data-route="{{ route('admin.settings.payment-gateways.api-settings.update') }}">
        <h6>@lang('Api Settings')</h6>
        <div class="mt-3">

            <div class="alert alert-info">
                <p>@lang("Use Mollie's APIs to accept payments, send payouts, and manage businesses online"). </p>
                <a href="https://mollie.com/" target="_blank" class="btn btn-primary">@lang('Get API Credentials')</a>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Mollie api key')  <span class="text-muted">(@lang('Required'))</span></label>
                        @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                            <input type="text" name="sandbox_mollie_api_key" class="form-control" value="{{ protected_data(setting('sandbox_mollie_api_key'), 'Mollie api key') }}">
                        @else
                            <input type="text" name="live_mollie_api_key" class="form-control" value="{{ setting('live_mollie_api_key') }}">
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6 col-md-12">
                    <div class="form-group">
                        <label>@lang('Status')</label>
                        <select class="form-control" name="mollie_status" id="mollie_status">
                            <option value="archived" @selected(setting('mollie_status') === 'archived')>@lang('Archived')</option>
                            <option value="active" @selected(setting('mollie_status') === 'active')>@lang('Active')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
