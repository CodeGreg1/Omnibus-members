@if (isset($subscription_sales_overview))
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column">
                    <h4>@lang('Sales Overview')</h4>
                    <p>@lang('In last 30 days sales of subscription.')</p>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-md-row flex-column">
                    <div class="d-flex flex-column mr-5 mb-3 mb-md-0">
                        <span class="text-muted">@lang('Total subscriptions')</span>
                        <h4>{{ $subscription_sales_overview['total_count'] }}</h4>
                    </div>

                    <div class="d-flex flex-column mr-5 mb-3 mb-md-0">
                        <span class="text-muted">@lang('Total signup revenue')</span>
                        <h4>{{ $subscription_sales_overview['amount_display'] }}</h4>
                    </div>

                    <div class="d-flex flex-column mb-3 mb-md-0">
                        <span class="text-muted">@lang('Renewal revenue')</span>
                        <h4>{{ $subscription_sales_overview['renewal_revenue'] }}</h4>
                    </div>
                </div>
                <canvas id="{{ $subscription_sales_overview['selector'] }}" class="chart" data-attributes='{{ json_encode($subscription_sales_overview) }}'></canvas>
            </div>
        </div>
    </div>
@endif
