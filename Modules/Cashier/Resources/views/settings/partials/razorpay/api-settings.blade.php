<div class="tab-pane fade" id="razorpay-gateway-api" role="tabpanel" aria-labelledby="razorpay-gateway-api-tab">
    <form id="razorpay-gateway-api-settings-form" data-route="{{ route('admin.settings.payment-gateways.api-settings.update') }}">
        <h6>@lang('Api Settings')</h6>
        <div class="mt-3">

            <div class="alert alert-info">
                <p>@lang("Use Razorpay's APIs to accept payments, send payouts, and manage businesses online"). </p>
                <a href="https://razorpay.com/" target="_blank" class="btn btn-primary">@lang('Get API Credentials')</a>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Razorpay key id')  <span class="text-muted">(@lang('Required'))</span></label>
                        @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                            <input type="text" name="sandbox_razorpay_id" class="form-control" value="{{ protected_data(setting('sandbox_razorpay_id'), 'Razorpay key id') }}">
                        @else
                            <input type="text" name="live_razorpay_id" class="form-control" value="{{ setting('live_razorpay_id') }}">
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Razorpay key secret')  <span class="text-muted">(@lang('Required'))</span></label>
                        @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                            <input type="text" name="sandbox_razorpay_secret" class="form-control" value="{{ protected_data(setting('sandbox_razorpay_secret'), 'Razorpay key secret') }}">
                        @else
                            <input type="text" name="live_razorpay_secret" class="form-control" value="{{ setting('live_razorpay_secret') }}">
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Webhook url')</label>
                        <input type="text" class="form-control" value="{{ route('cashier.webhook.handle', 'razorpay') }}" disabled>
                        <small class="form-text text-muted">
                            @lang('Add this to your razorpay webhook portal')
                        </small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <label>@lang('Razorpay webhook secret')  <span class="text-muted">(@lang('Required'))</span></label>
                            <div class="dropdown d-inline mr-2">
                                <button class="btn dropdown-toggle" type="button" id="razorpayEventsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @lang('Show events')
                                </button>
                                <div class="dropdown-menu px-3 py-3 text-sm" style="width: 290px;">
                                    <strong>@lang('Required events')</strong>
                                    <ul class="list-unstyled mb-0 mt-1">
                                        @foreach ($razorpay_webhook_events as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <div class="input-group">
                                @if (setting('cashier_mode', 'sandbox') === 'sandbox')
                                    <input type="text" name="sandbox_razorpay_webhook_secret" class="form-control" value="{{ protected_data(setting('sandbox_razorpay_webhook_secret'),'Razorpay webhook secret') }}">
                                @else
                                    <input type="text" name="live_razorpay_webhook_secret" class="form-control" value="{{ setting('live_razorpay_webhook_secret') }}">
                                @endif
                            </div>
                        </div>
                        <small class="form-text text-muted mb-3">
                            @lang('The webhook signing secret, used to generate webhook signatures and webhook event verification')
                        </small>
                        <div class="alert alert-info">
                            <p>@lang("Use your webhook url and razorpay webhook secret as secret when adding your webhook to razorpay app."). </p>
                            <a href="https://dashboard.razorpay.com/app/webhooks" target="_blank" class="btn btn-primary">@lang('Add webhook')</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6 col-md-12">
                    <div class="form-group mb-0">
                        <label>@lang('Status')</label>
                        <select class="form-control" name="razorpay_status" id="razorpay_status">
                            <option value="archived" @selected(setting('razorpay_status') === 'archived')>@lang('Archived')</option>
                            <option value="active" @selected(setting('razorpay_status') === 'active')>@lang('Active')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
