<div class="tab-pane fade" id="paypal-gateway-api" role="tabpanel" aria-labelledby="paypal-gateway-api-tab">
    <form id="paypal-gateway-api-settings-form" data-route="{{ route('admin.settings.payment-gateways.api-settings.update') }}">
        <h6>@lang('Api Settings')</h6>
        <div class="mt-3">
            <div class="alert alert-info">
                <p>@lang("Whether you're building an online, mobile or in-person payment solution, create a PayPal Developer account and find the resources you need to test & go live").</p>
                <a href="https://developer.paypal.com/home" target="_blank" class="btn btn-primary">@lang('Get API Credentials')</a>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Paypal client key')  <span class="text-muted">(@lang('Required'))</span></label>
                        @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                            <input type="text" name="sandbox_paypal_client_key" class="form-control" value="{{ protected_data(setting('sandbox_paypal_client_key'),'Paypal client key') }}">
                        @else
                            <input type="text" name="live_paypal_client_key" class="form-control" value="{{ setting('live_paypal_client_key') }}">
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Paypal client secret')  <span class="text-muted">(@lang('Required'))</span></label>
                        @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                            <input type="text" name="sandbox_paypal_client_secret" class="form-control" value="{{ protected_data(setting('sandbox_paypal_client_secret'), 'Paypal client secret') }}">
                        @else
                            <input type="text" name="live_paypal_client_secret" class="form-control" value="{{ setting('live_paypal_client_secret') }}">
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <label for="paypal_webhook_id">@lang('Paypal webhook id')  <span class="text-muted">(@lang('Required'))</span></label>
                            <div class="dropdown d-inline mr-2">
                                <button class="btn dropdown-toggle" type="button" id="paypalEventsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @lang('Show events')
                                </button>
                                <div class="dropdown-menu px-3 py-3 text-sm" style="width: 290px;">
                                    <strong>@lang('Required events')</strong>
                                    <ul class="list-unstyled mb-0 mt-1">
                                        @foreach ($paypal_webhook_events as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <div class="input-group">
                                @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                                    <input type="text" name="sandbox_paypal_webhook_id" class="form-control" value="{{ protected_data(setting('sandbox_paypal_webhook_id'), 'Paypal webhook id') }}">
                                @else
                                    <input type="text" name="live_paypal_webhook_id" class="form-control" value="{{ setting('live_paypal_webhook_id') }}">
                                @endif

                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-install-gateway-webhook" type="button" data-route="{{ route('admin.settings.payment-gateways.install-webhook', 'paypal') }}">
                                        @if (setting(setting('cashier_mode', 'sandbox') . '_paypal_webhook_id'))
                                            @lang('Re-install')
                                        @else
                                            @lang('Install')
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            @lang('The ID of the webhook from paypal. Used for verifying webhook events from paypal')
                        </small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Webhook url')</label>
                        <input type="text" class="form-control" value="{{ route('cashier.webhook.handle', 'paypal') }}" disabled>
                        <small class="form-text text-muted">
                            @lang('Add this to your paypal dashboard portal')
                        </small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6 col-md-12">
                    <div class="form-group mb-0">
                        <label>@lang('Status')</label>
                        <select class="form-control" name="paypal_status" id="paypal_status">
                            <option value="archived" @selected(setting('paypal_status') === 'archived')>@lang('Archived')</option>
                            <option value="active" @selected(setting('paypal_status') === 'active')>@lang('Active')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
