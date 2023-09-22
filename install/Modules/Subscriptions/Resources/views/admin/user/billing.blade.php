@extends('users::layouts.master')

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.users.index') }}"> {!! config('users.name') !!}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{$pageTitle}}</a>
        </li>
    </ol>
@endsection

@section('scripts')
<script>
    window.subscription = @json($subscription ?? '{}');
</script>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-sm-12 col-lg-4">
            @include('users::admin.partials.show-profile-widget')
        </div>

        <div class="col-12 col-sm-12 col-lg-8">
            @include('users::admin.partials.show-user-menus')

            <div class="card">
                <div class="card-header">
                    <div class="w-100 d-flex align-items-center justify-content-between">
                        <h6>@lang('Current package')</h6>
                        @include('cashier::partials.live-mode-status')
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border">
                        <li class="media">
                            <div class="media-body">
                                <div class="media-right">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.subscriptions.show', $subscription->id) }}">
                                            @lang('View subscription')
                                        </a>
                                        @if (!$subscription->canceled() && $subscription->valid())
                                            <a class="dropdown-item btn-cancel-subscription text-danger" href="#" data-id="{{ $subscription->id }}">
                                                @lang('Cancel subscription')
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="media-title">
                                    <strong class="mr-2">{{ $subscription->item->price->package->name }}</strong>
                                    <span class="badge badge-sm badge-{{ $subscription->getStatusLabel() }}">{{ Str::title($subscription->status) }}</span>
                                </div>
                                <div class="text-muted text-small mb-2">
                                    {{ $subscription->item->price->getBillingDescription() }}
                                    @if ($subscription->isRecurring())
                                         <div class="bullet"></div>
                                    @endif
                                    @if ($status === 'trialing')
                                        @lang('Trials ends on :end and payment amounting :total will be collected', [
                                            'end' => $subscription->ends_at->toUserTimezone()->isoFormat('lll'),
                                            'total' => $subscription->getTotal()
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
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @if (!$subscription->canceled())
        @include('subscriptions::admin.modals.upgrade-package')
    @endif
@endsection
