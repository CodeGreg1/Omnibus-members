@extends('subscriptions::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.subscriptions.index') }}"> @lang('Subscriptions')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('module-actions')
    <div class="d-flex align-items-center justify-content-between mt-4 mt-lg-0">
        @if (!$subscription->canceled() && $subscription->valid() && !$subscription->ended())
            <div class="btn-group ml-lg-2 mr-3">
                <a href="javascript:void(0)" class="btn btn-danger btn-cancel-subscription mr-2" data-id="{{ $subscription->id }}">
                    @lang('Cancel subscription')
                </a>
            </div>
        @endif

        @include('cashier::partials.live-mode-status')
    </div>
@endsection

@section('scripts')
<script>
    window.subscription = @json($subscription);
</script>
@endsection

@section('content')
<input type="hidden" id="Subscription_id" value="{{ $subscription->id }}">
    <div class="row admin-view-user">
        <div class="col-12 col-sm-12 col-md-4">
            <div class="card profile-widget admin-view-user">
                <div class="profile-widget-header">
                    <img alt="User" src="{{ $subscription->subscribable->present()->avatar ?? '/upload/users/avatar.png' }}" class="rounded-circle profile-widget-picture">
                    <div class="profile-widget-items">
                        <div class="profile-widget-item">
                            <div class="profile-widget-item-label full-name">{{ $subscription->subscribable->full_name ?? __('N/A') }}</div>
                            <div class="profile-widget-item-value email text-muted">{{ protected_data($subscription->subscribable->email, 'email') }}</div>
                            <div class="profile-widget-item-value email text-muted user-role">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-widget-description pt-0 pb-0">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Total spent')</b>
                            <span>{{ $totalSpent }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Since')</b>
                            <span>{{ $subscription->subscribable->created_at_for_humans }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <b>@lang('Gateway')</b>
                            <span>{{ str($subscription->gateway)->title()->value }}</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <div class="summary">
                        <div class="summary-item mt-1">
                            <ul class="list-unstyled list-unstyled-border">
                                <li class="media">
                                    <div class="media-body">
                                        <div class="media-right">{{ $subscription->getTotal(true, $subscription->item->currency) }}</div>
                                        <div class="media-title">
                                            <strong>{{ $subscription->getPackageName() }}</strong>
                                        </div>
                                        <div class="text-muted text-small d-flex flex-column mb-2">
                                            <span>
                                                {{ $subscription->item->price->getBillingDescription() }}
                                            </span>
                                            <span>
                                                @if ($status === 'trialing')
                                                    @lang('Trials ends on :end and payment amounting :total will be collected', [
                                                        'end' => $subscription->ends_at->isoFormat('lll'),
                                                        'total' => $subscription->getTotal()
                                                    ])
                                                @elseif ($status === 'cancelled')
                                                    @lang('Cancelled last :date', [
                                                        'date' => $subscription->canceled_at->isoFormat('lll')
                                                    ])
                                                @elseif ($status === 'active')
                                                    @lang('Next billing on :date', [
                                                        'date' => $subscription->getNextBillingDate()
                                                    ])
                                                @elseif ($status === 'past_due')
                                                    @lang('Payment due last :date', [
                                                        'date' => $subscription->getNextBillingDate()
                                                    ])
                                                @else
                                                    @lang('Ended las :end', [
                                                        'end' => $subscription->getGracePeriodEndDate()->isoFormat('lll')
                                                    ])
                                                @endif
                                            </span>
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
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-8">
            <h6>@lang('Payment history')</h6>
            <div id="subscription-payments-datatable"></div>
        </div>
    </div>
@endsection
