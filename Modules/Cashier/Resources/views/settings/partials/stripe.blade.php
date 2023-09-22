<div class="row">
    <div class="order-first order-lg-last col-lg-3 col-md-3 col-xm-12">
        <ul class="nav flex-column gateway-settings-tab" role="tablist" id="stripe_gateway-settings-tab">
            <li class="nav-item">
                <a
                    class="nav-link active"
                    id="stripe-gateway-general-settings-tab"
                    data-toggle="tab"
                    href="#stripe-gateway-general-settings"
                    role="tab"
                    aria-controls="stripe-gateway-general-settings"
                    aria-selected="true"
                    data-value="stripe-gateway-general-settings"
                    data-form="#stripe-gateway-general-settings-settings-form"
                >@lang('General Settings')</a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    id="stripe-gateway-api-tab"
                    data-toggle="tab"
                    href="#stripe-gateway-api"
                    role="tab"
                    aria-controls="stripe-gateway-api"
                    aria-selected="false"
                    data-value="stripe-gateway-api"
                    data-form="#stripe-gateway-api-settings-form"
                >@lang('API Settings')</a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    id="stripe-gateway-deposit-tab"
                    data-toggle="tab"
                    href="#stripe-gateway-deposit"
                    role="tab"
                    aria-controls="stripe-gateway-deposit"
                    aria-selected="false"
                    data-value="stripe-gateway-deposit"
                    data-form="#stripe-gateway-deposit-settings-form"
                >@lang('Deposit Settings')</a>
            </li>
        </ul>
    </div>

    <div class="order-last order-lg-first col-lg-9 col-md-9 col-xm-12">
        <div class="tab-content" id="stripe-gateway-setting-tablist-content">
            @include('cashier::settings.partials.stripe.general-settings')
            @include('cashier::settings.partials.stripe.api-settings')
            @include('cashier::settings.partials.stripe.deposit-settings')
        </div>
    </div>
</div>
