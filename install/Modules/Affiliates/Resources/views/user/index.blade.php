@extends('affiliates::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
@endsection

@section('policies')
    app.policies = {!! json_encode($policies) !!};
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Affiliates')</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')
    @if (isset($affiliate_overview))
        <div id="user-affiliates-datatable"></div>
    @else
        @if (auth()->user()->affiliate)
            @if (auth()->user()->affiliate->rejected_at)
                <div class="alert alert-danger alert-has-icon">
                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                    <div class="alert-body">
                        <div class="alert-title">
                            @lang('Affiliate membership rejected')
                        </div>
                        @lang('Your affiliate membership request is rejected.')
                    </div>
                </div>
            @elseif (!auth()->user()->affiliate->active && !auth()->user()->affiliate->approved)
                <div class="alert alert-info alert-has-icon">
                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                    <div class="alert-body">
                        <div class="alert-title">
                            @lang('Affiliate membership pending')
                        </div>
                        @lang('Your affiliate membership request is for approval.')
                    </div>
                </div>
            @elseif (!auth()->user()->affiliate->active && auth()->user()->affiliate->approved)
                <div class="alert alert-warning alert-has-icon">
                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                    <div class="alert-body">
                        <div class="alert-title">
                            @lang('Affiliate membership disabled')
                        </div>
                        @lang('Your affiliate membership is disabled.')
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="d-flex flex-column mb-3">
                            <span class="mb-1">@lang('Your code:') <strong>{{ $affiliate->code }}</strong></span>
                            <span>@lang('Use this url:')</span>
                            <div class="d-flex cursor-pointer btn-copy-affiliate-code-clipboard" data-toggle="tooltip" data-original-title="@lang('Click to copy')">
                                <code>{{ url('/?r='.$affiliate->code) }}</code>
                                <i class="fas fa-copy text-muted ml-3"></i>
                            </div>
                        </div>
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary text-white">
                                <i class="fas fa-mouse"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4 class="text-xs">@lang('Total Clicks')</h4>
                                </div>
                                <div class="card-body">
                                    {{ $total_clicks }}
                                </div>
                            </div>
                        </div>

                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success text-white">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4 class="text-xs">@lang('Total Conversion')</h4>
                                </div>
                                <div class="card-body">
                                    {{ $registered_count }}
                                    <span class="text-sm ml-1 {{ $conversion_rate > 50 ? 'text-success' : 'text-danger' }}">{{ $conversion_rate }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-clock mr-2 text-warning"></i>
                                <h4 class="wallet-title">@lang('Pending Withdrawables')</h4>
                            </div>
                            <div class="card-body">
                                <div class="summary">
                                    <div class="summary-item mt-0">
                                        <ul class="list-unstyled list-unstyled-border mb-0">
                                            @foreach ($pendingWithdrawables as $currency => $amount)
                                                <li class="media">
                                                    <div class="media-body wallet-item-link">
                                                        <div class="media-right">{{ $amount }}</div>
                                                        <div class="media-title">
                                                            {{ $currency }}
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check mr-2 text-success"></i>
                                    <h4 class="wallet-title">@lang('Withdrawables')</h4>
                                </div>
                                @if ($hasWithdrawables)
                                    <div class="card-header-action ml-auto">
                                        <button type="button" class="btn btn-primary btn-commission-withdraw-all" data-toggle="tooltip" data-original-title="@lang('Withdraw all available commissions')">
                                            <i class="fas fa-money-bill"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="summary">
                                    <div class="summary-item mt-0">
                                        <ul class="list-unstyled list-unstyled-border mb-0">
                                            @foreach ($approvedWithdrawables as $currency => $amount)
                                                <li class="media">
                                                    <div class="media-body wallet-item-link">
                                                        <div class="media-right">{{ $amount }}</div>
                                                        <div class="media-title">
                                                            {{ $currency }}
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-8">
                        <div class="d-flex align-items-end justify-content-between mb-2">
                            <h5 class="mb-2">@lang('Referred users')</h5>
                            <div>
                                <select class="form-control" id="affiliate-referral-level">
                                    @for ($i = 1; $i <= $levels; $i++)
                                        <option value="{{ $i }}">
                                            @lang('Level :level', ['level' => $i])
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div id="user-affiliate-referrals-datatable"></div>
                    </div>
                </div>
            @endif
        @else
            <div class="alert alert-primary alert-has-icon">
                <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                <div class="alert-body">
                    <div class="alert-title">
                        @lang('Not an affiliate')
                    </div>
                    <div class="d-flex flex-column">
                        <p>@lang('You are not a member of affiliate. To become a member please click the button below.')</p>
                        <div class="mt-2">
                            <a href="javascript:void(0)" role="button" class="btn btn-warning" id="btn-create-affiliate-user">@lang('Become a member')</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endsection
