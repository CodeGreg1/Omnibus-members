@extends('wallet::layouts.master')

@section('module-styles')
    <link rel="stylesheet" href="{{ url('plugins/jquery-datatable/css/jquery-datatable.css') }}">
@endsection

@section('module-scripts')
    <script src="{{ url('plugins/jquery-datatable/js/jquery-datatable.js') }}"></script>
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script>
        window.allow_wallet_exchange_otp = parseInt({!! $allow_wallet_exchange_otp !!});
        window.allow_send_money_otp = parseInt({!! $allow_send_money_otp !!});
    </script>
@endsection

@section('scripts')
    <script>
        window.currencies = @json($wallets->values());
    </script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="row" id="wallets">
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="wallet-title">@lang(':name Wallet', [
                        'name' => $defaultWallet->name
                    ])</h4>
                </div>
                <div class="card-body">
                    <div class="summary">
                        <div class="summary-info">
                            <h4 class="wallet-balance">{{ currency_format($defaultWallet->balance, $defaultWallet->code) }}</h4>
                            <div class="text-muted wallet-code">{{ $defaultWallet->code }}</div>
                            @if (setting('allow_send_money') === 'enable')
                                <button type="button" class="btn btn-light btn-icon icon-left mt-2 btn-send-money-show-modal {{ !$defaultWallet->balance ? 'disabled' : '' }}" data-toggle="modal" data-target="#wallet-send-money-modal" @disabled(!$defaultWallet->balance)>
                                    <i class="fas fa-paper-plane"></i> @lang('Send')
                                </button>
                            @endif
                        </div>
                        <div class="summary-item">
                            <h6>@lang('All Wallets') <span class="text-muted">({{ $wallets->count()}} @lang($wallets->count() > 1 ? 'Wallets' : 'Wallet'))</span></h6>
                            <ul class="list-unstyled list-unstyled-border mb-0">
                                @foreach ($wallets as $key => $wallet)
                                    <li class="media {{ $wallet->id === $defaultWallet->id ? 'active' : '' }}" data-code="{{ $wallet->code }}">
                                        <a href="javascript:void(0)" class="media-body wallet-item-link {{ $wallet->status ? '' : 'disabled text-muted' }}" @disabled(!$wallet->status)>
                                            <div class="media-right">{{ currency_format($wallet->balance, $wallet->code) }}</div>
                                            <div class="media-title">
                                                {{ $wallet->code }}
                                            </div>
                                            <div class="text-muted text-small">{{ $wallet->name }}</div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @if ($wallets->count() > 1)
                    <div class="card-footer pt-0">
                        <button type="button" class="btn btn-icon icon-left btn-block btn-primary wallet-exchange-link {{ !$defaultWallet->balance ? 'disabled' : '' }}" data-toggle="modal" data-target="#wallet-exchange-modal" @disabled(!$defaultWallet->balance)>
                            <i class="fas fa-undo"></i> @lang('Convert')
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-12 col-md-8">
            <h5>@lang('Your Transactions')</h5>
            <div id="wallet-transactions-datatable"></div>
        </div>
    </div>
@endsection

@section('modals')
    @if (setting('allow_send_money') === 'enable')
        @include('wallet::modals.wallet-send')
    @endif

    @include('wallet::modals.confirm-otp')
    @if ($wallets->count() > 1)
        @include('wallet::modals.wallet-exchange')
    @endif
@endsection
