@extends('settings::layouts.master')

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
            <a href="{{ route('admin.users.index') }}"> {!! config('users.name') !!}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{$pageTitle}}</a>
        </li>
    </ol>
@endsection

@section('content')
    @include('partials.messages')

    <div class="row admin-view-user">
        <div class="col-12 col-sm-12 col-md-4">
            @include('users::admin.partials.show-profile-widget')
        </div>

        <div class="col-12 col-sm-12 col-md-8">
            @include('users::admin.partials.show-user-menus')

            <div class="card">
                <div class="card-header">
                    <h4>@lang('Deposit')</h4>
                </div>
                <div class="card-body user-overview-cards">
                    @foreach ($depositsOverview as $currency => $overview)
                        <div class="row px-2">
                            @foreach ($overview as $item)
                                <div class="col-lg-4 col-md-12 col-sm-12 col-12 px-2">
                                    <div class="card card-statistic-1 mb-3">
                                        <div class="card-wrap">
                                            <div class="card-header" style="line-height: 12px;">
                                                <span class="text-xs">{{ $item['label'] }}</span>
                                            </div>
                                            <div class="card-body pb-3">
                                                {{ $item['value'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="card-body">
                    <h6 class="mb-3">@lang('Deposit Transactions')</h6>
                    <div id="user-overview-deposit-transactions-datatable" data-user="{{ $user->id }}"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>@lang('Withdrawals')</h4>
                </div>
                <div class="card-body user-overview-cards">
                    @foreach ($withdrawalsOverview as $currency => $overview)
                        <div class="row px-2">
                            @foreach ($overview as $item)
                                <div class="col-lg-4 col-md-12 col-sm-12 col-12 px-2">
                                    <div class="card card-statistic-1 mb-3">
                                        <div class="card-wrap">
                                            <div class="card-header" style="line-height: 12px;">
                                                <span class="text-xs">{{ $item['label'] }}</span>
                                            </div>
                                            <div class="card-body pb-3">
                                                {{ $item['value'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="card-body">
                    <h6 class="mb-3">@lang('Withdrawal Transactions')</h6>
                    <div id="user-overview-withdrawal-transactions-datatable" data-user="{{ $user->id }}"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>@lang('All Transactions')</h4>
                </div>

                <div class="card-body">
                    <div id="user-overview-transactions-datatable" data-user="{{ $user->id }}"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @if (setting('allow_wallet') === 'enable')
        @include('wallet::admin.modals.add-balance')
        @include('wallet::admin.modals.deduct-balance')
    @endif
@endsection
