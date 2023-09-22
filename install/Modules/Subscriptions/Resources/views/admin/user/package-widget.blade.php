<div class="card card-package-widget">
    <div class="card-body">
        <div class="d-flex flex-column align-items-start">
            @include('cashier::partials.live-mode-status', ['class' => 'mb-2'])

            <span class="badge bg-label-primary">{{ $subscription->item->price->package->name }}</span>
            <div class="d-flex justify-content-center mt-3">
                <sup class="h5 pricing-currency mt-3 mb-0 me-1 text-primary">{{ $subscription->currency }}</sup>
                <h1 class="display-5 mb-0 text-primary">{{ $subscription->getTotal(true, $subscription->item->currency) }}</h1>
                <sub class="pricing-duration mt-auto mb-3 ml-1">
                    @if ($subscription->isRecurring())
                        / {{ $subscription->item->price->term->getTermDiplayLabel() }}
                    @else
                        @lang('forever')
                    @endif
                </sub>
            </div>
        </div>

        <ul class="mb-0 mt-2 pl-3">
            @foreach ($subscription->item->price->package->features as $feature)
                <li>
                    <span>{{ $feature->title }}</span>
                </li>
            @endforeach
        </ul>

        @if ($subscription->isRecurring())
            <div class="d-flex justify-content-between align-items-center mb-1 mt-3">
                <span>{{ $subscription->completion->getDurationTitle() }}</span>
                <span>{{ $subscription->completion->getPercentageCompletion() }}% @lang('Completed')</span>
            </div>
            <div class="progress mb-1" data-height="10" style="height: 10px;">
                <div class="progress-bar" role="progressbar" data-width="{{ $subscription->completion->getPercentageCompletion() }}%" aria-valuenow="{{ $subscription->completion->getPercentageCompletion() }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $subscription->completion->getPercentageCompletion() }}%;"></div>
            </div>
            <span>{{ $subscription->completion->getRemaining() }} {{ $subscription->completion->duration }} @lang('remaining')
                @if ($subscription->onTrial())
                    <span class="text-muted text-sm">(@lang('Trial'))</span>
                @endif
            </span>
        @endif
    </div>
</div>
