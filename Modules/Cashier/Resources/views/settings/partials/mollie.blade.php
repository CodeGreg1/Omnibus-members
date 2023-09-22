<div class="row">
    <div class="order-first order-lg-last col-lg-3 col-md-3 col-xm-12">
        <ul class="nav flex-column gateway-settings-tab" role="tablist" id="mollie_gateway-settings-tab">
            <li class="nav-item">
                <a
                    class="nav-link active"
                    id="mollie-gateway-general-settings-tab"
                    data-toggle="tab"
                    href="#mollie-gateway-general-settings"
                    role="tab"
                    aria-controls="mollie-gateway-general-settings"
                    aria-selected="true"
                    data-value="mollie-gateway-general-settings"
                    data-form="#mollie-gateway-general-settings-settings-form"
                >@lang('General Settings')</a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    id="mollie-gateway-api-tab"
                    data-toggle="tab"
                    href="#mollie-gateway-api"
                    role="tab"
                    aria-controls="mollie-gateway-api"
                    aria-selected="false"
                    data-value="mollie-gateway-api"
                    data-form="#mollie-gateway-api-settings-form"
                >@lang('API Settings')</a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    id="mollie-gateway-deposit-tab"
                    data-toggle="tab"
                    href="#mollie-gateway-deposit"
                    role="tab"
                    aria-controls="mollie-gateway-deposit"
                    aria-selected="false"
                    data-value="mollie-gateway-deposit"
                    data-form="#mollie-gateway-deposit-settings-form"
                >@lang('Deposit Settings')</a>
            </li>
        </ul>
    </div>

    <div class="order-last order-lg-first col-lg-9 col-md-9 col-xm-12">
        <div class="tab-content" id="mollie-gateway-setting-tablist-content">
            @include('cashier::settings.partials.mollie.general-settings')
            @include('cashier::settings.partials.mollie.api-settings')
            @include('cashier::settings.partials.mollie.deposit-settings')
        </div>
    </div>
</div>
