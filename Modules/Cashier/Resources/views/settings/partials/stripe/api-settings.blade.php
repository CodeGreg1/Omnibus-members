<div class="tab-pane fade" id="stripe-gateway-api" role="tabpanel" aria-labelledby="stripe-gateway-api-tab">
    <form id="stripe-gateway-api-settings-form" data-route="{{ route('admin.settings.payment-gateways.api-settings.update') }}">
        <h6>@lang('Api Settings')</h6>
        <div class="mt-3">

            <div class="alert alert-info">
                <p>@lang("Use Stripe's APIs to accept payments, send payouts, and manage businesses online"). </p>
                <a href="https://stripe.com/" target="_blank" class="btn btn-primary">@lang('Get API Credentials')</a>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Stripe publishable key')  <span class="text-muted">(@lang('Required'))</span></label>
                        @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                            <input type="text" name="sandbox_stripe_publishable_key" class="form-control" value="{{ protected_data(setting('sandbox_stripe_publishable_key'),'Stripe publishable key') }}">
                        @else
                            <input type="text" name="live_stripe_publishable_key" class="form-control" value="{{ setting('live_stripe_publishable_key') }}">
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Stripe secret key')  <span class="text-muted">(@lang('Required'))</span></label>
                        @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                            <input type="text" name="sandbox_stripe_secret_key" class="form-control" value="{{ protected_data(setting('sandbox_stripe_secret_key'),'Stripe secret key') }}">
                        @else
                            <input type="text" name="live_stripe_secret_key" class="form-control" value="{{ setting('live_stripe_secret_key') }}">
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <label>@lang('Stripe webhook secret')  <span class="text-muted">(@lang('Required'))</span></label>
                            <div class="dropdown d-inline mr-2">
                                <button class="btn dropdown-toggle" type="button" id="stripeEventsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @lang('Show events')
                                </button>
                                <div class="dropdown-menu px-3 py-3 text-sm" style="width: 290px;">
                                    <strong>@lang('Required events')</strong>
                                    <ul class="list-unstyled mb-0 mt-1">
                                        @foreach ($stripe_webhook_events as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <div class="input-group">
                                @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                                    <input type="text" name="sandbox_stripe_webhook_secret" class="form-control" value="{{ protected_data(setting('sandbox_stripe_webhook_secret'),'Stripe webhook secret') }}">
                                @else
                                    <input type="text" name="live_stripe_webhook_secret" class="form-control" value="{{ setting('live_stripe_webhook_secret') }}">
                                @endif

                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-install-gateway-webhook" type="button" data-route="{{ route('admin.settings.payment-gateways.install-webhook', 'stripe') }}">
                                        @if (setting(setting('cashier_mode', 'sandbox') . '_stripe_webhook_secret'))
                                            @lang('Re-install')
                                        @else
                                            @lang('Install')
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            @lang('The webhook signing secret, used to generate webhook signatures and webhook event verification')
                        </small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Webhook url')</label>
                        <input type="text" class="form-control" value="{{ route('cashier.webhook.handle', 'stripe') }}" disabled>
                        <small class="form-text text-muted">
                            @lang('Add this to your stripe webhook portal')
                        </small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6 col-md-12">
                    <div class="form-group mb-0">
                        <label>@lang('Status')</label>
                        <select class="form-control" name="stripe_status" id="stripe_status">
                            <option value="archived" @selected(setting('stripe_status') === 'archived')>@lang('Archived')</option>
                            <option value="active" @selected(setting('stripe_status') === 'active')>@lang('Active')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
