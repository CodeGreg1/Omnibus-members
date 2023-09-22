@if (isset($subscription_widgets))
    @foreach ($subscription_widgets as $subscription_widget)
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-{{$subscription_widget['color']}}">
                    <i class="{{$subscription_widget['icon']}}"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>{{ trans($subscription_widget['label']) }}</h4>
                    </div>
                    <div class="card-body">
                        {{$subscription_widget['total']}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
