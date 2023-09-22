@if (isset($user_wallets))
    <div class="col-12">
        <div class="row">
            @foreach ($user_wallets as $wallet)
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ $wallet->currency['name'] }}</h4>
                            </div>
                            <div class="card-body pb-3">
                                {{ $wallet->amount }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
