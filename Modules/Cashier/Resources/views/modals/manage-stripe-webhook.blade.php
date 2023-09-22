<div class="modal fade" tabindex="-1" role="dialog" id="manage-stripe-webhook-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Manage webhook')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <strong>@lang('Required events')</strong>
                <ul class="list-unstyled pl-2 mt-2">
                    @foreach ($stripe_webhook_events as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>

                @if (!setting(setting('cashier_mode', 'sandbox') . '_stripe_webhook_secret'))
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary btn-install-gateway-webhook" data-route="{{ route('admin.settings.payment-gateways.install-webhook', 'stripe') }}">@lang('Install webhook')</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
