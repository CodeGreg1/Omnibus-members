<div class="row">
    <div class="order-first order-lg-last col-lg-3 col-md-3 col-xm-12">
        <ul class="nav flex-column gateway-settings-tab" role="tablist" id="paypal_gateway-settings-tab">
            <li class="nav-item">
                <a
                    class="nav-link active"
                    id="paypal-gateway-general-esettings-tab"
                    data-toggle="tab"
                    href="#paypal-gateway-general-esettings"
                    role="tab"
                    aria-controls="paypal-gateway-general-esettings"
                    aria-selected="true"
                    data-value="paypal-gateway-general-esettings"
                    data-form="#paypal-gateway-general-esettings-settings-form"
                >@lang('General Settings')</a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    id="paypal-gateway-api-tab"
                    data-toggle="tab"
                    href="#paypal-gateway-api"
                    role="tab"
                    aria-controls="paypal-gateway-api"
                    aria-selected="false"
                    data-value="paypal-gateway-api"
                    data-form="#paypal-gateway-api-settings-form"
                >@lang('API Settings')</a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    id="paypal-gateway-deposit-tab"
                    data-toggle="tab"
                    href="#paypal-gateway-deposit"
                    role="tab"
                    aria-controls="paypal-gateway-deposit"
                    aria-selected="false"
                    data-value="paypal-gateway-deposit"
                    data-form="#paypal-gatway-deposit-settings-form"
                >@lang('Deposit Settings')</a>
            </li>
        </ul>
    </div>
    <div class="order-last order-lg-first col-lg-9 col-md-9 col-xm-12">
        <div class="tab-content" id="paypal-gateway-setting-tablist-content">
            @include('cashier::settings.partials.paypal.general-settings')
            @include('cashier::settings.partials.paypal.api-settings')
            @include('cashier::settings.partials.paypal.deposit-settings')
        </div>
    </div>
</div>
