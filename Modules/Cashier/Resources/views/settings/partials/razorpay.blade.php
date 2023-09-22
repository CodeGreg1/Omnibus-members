<div class="row">
    <div class="order-first order-lg-last col-lg-3 col-md-3 col-xm-12">
        <ul class="nav flex-column gateway-settings-tab" role="tablist" id="razorpay_gateway-settings-tab">
            <li class="nav-item">
                <a
                    class="nav-link active"
                    id="razorpay-gateway-general-settings-tab"
                    data-toggle="tab"
                    href="#razorpay-gateway-general-settings"
                    role="tab"
                    aria-controls="razorpay-gateway-general-settings"
                    aria-selected="true"
                    data-value="razorpay-gateway-general-settings"
                    data-form="#razorpay-gateway-general-settings-settings-form"
                >@lang('General Settings')</a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    id="razorpay-gateway-api-tab"
                    data-toggle="tab"
                    href="#razorpay-gateway-api"
                    role="tab"
                    aria-controls="razorpay-gateway-api"
                    aria-selected="false"
                    data-value="razorpay-gateway-api"
                    data-form="#razorpay-gateway-api-settings-form"
                >@lang('API Settings')</a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    id="razorpay-gateway-deposit-tab"
                    data-toggle="tab"
                    href="#razorpay-gateway-deposit"
                    role="tab"
                    aria-controls="razorpay-gateway-deposit"
                    aria-selected="false"
                    data-value="razorpay-gateway-deposit"
                    data-form="#razorpay-gateway-deposit-settings-form"
                >@lang('Deposit Settings')</a>
            </li>
        </ul>
    </div>

    <div class="order-last order-lg-first col-lg-9 col-md-9 col-xm-12">
        <div class="tab-content" id="razorpay-gateway-setting-tablist-content">
            @include('cashier::settings.partials.razorpay.general-settings')
            @include('cashier::settings.partials.razorpay.api-settings')
            @include('cashier::settings.partials.razorpay.deposit-settings')
        </div>
    </div>
</div>
