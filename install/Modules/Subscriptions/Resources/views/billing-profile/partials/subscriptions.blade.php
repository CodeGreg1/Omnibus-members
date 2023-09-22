<div class="card card-form" id="subscriptions">
    <div class="card-header">
        <h4><i class="fas fa-key"></i> @lang('Subscriptions')</h4>
    </div>
    <div class="card-body">
        <ul class="list-unstyled list-unstyled-border">
            @if ($subscription)
                <li class="media">
                    <div class="media-body">
                        <div class="media-right">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('user.subscriptions.show', $subscription->id) }}">
                                    @lang('View subscription')
                                </a>
                                @if ($subscription->active())
                                    <a class="dropdown-item" href="{{route('user.subscriptions.pricings.index')}}">
                                        @lang('Update subscription')
                                    </a>

                                    @can('user.subscriptions.cancel')
                                        @if (!$subscription->canceled())
                                            <a class="dropdown-item text-danger btn-cancel-user-subscription" href="javascript:void(0)" data-id="{{$subscription->id}}">@lang('Cancel subscription')</a>
                                        @endif
                                    @endcan

                                @endif
                            </div>
                        </div>
                        <div class="media-title">
                            <strong class="mr-2">{{ $subscription->item->price->package->name }}</strong>
                        </div>
                        <div class="text-muted text-small mb-2">
                            {{ $subscription->item->price->getBillingDescription() }}
                            @if ($subscription->isRecurring())
                                <div class="bullet"></div>
                            @endif

                            @if ($status === 'trialing')
                                @lang('Trials ends on :end ', [
                                    'end' => $subscription->ends_at->toUserTimezone()->isoFormat('lll')
                                ])
                            @elseif ($status === 'cancelled')
                                @lang('Cancelled last :date', [
                                    'date' => $subscription->canceled_at->toUserTimezone()->isoFormat('lll')
                                ])
                            @elseif ($status === 'active')
                                @if ($subscription->isRecurring())
                                    @lang('Next billing on :date', [
                                        'date' => $subscription->getNextBillingDate()
                                    ])
                                @endif
                            @elseif ($status === 'past_due')
                                @lang('Payment due last :date', [
                                    'date' => $subscription->getNextBillingDate()
                                ])
                            @else
                                @lang('Ended last :end', [
                                    'end' => $subscription->getGracePeriodEndDate()->toUserTimezone()->isoFormat('lll')
                                ])
                            @endif
                        </div>
                        @if ($status === 'past_due')
                            <div class="alert alert-warning">
                                @lang('Past due, and the :plan features will be disabled until :date is reached.', [
                                    'plan' => $subscription->getPackageName(),
                                    'date' => $subscription->getGracePeriodEndDate()->toUserTimezone()->toUserFormat()
                                ])
                            </div>
                        @else
                                <span class="badge badge-{{ $subscription->getStatusLabel() }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $subscription->getStatusDisplay() }}">
                                {{ $subscription->getStatusTitle() }}
                            </span>
                        @endif
                    </div>
                </li>
            @else
                <div class="d-flex align-items-center justify-content-center flex-column">
                    <p class="text-center pt-2">@lang('No subscription yet')</p>
                    <a href="{{ route('user.subscriptions.pricings.index') }}" class="btn btn-primary">@lang('Subscribe now')</a>
                </div>
            @endif
        </ul>
    </div>
</div>
