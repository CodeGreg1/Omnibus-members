@extends('settings::layouts.master')

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> @lang('Settings')</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-3 col-xm-12">
            @include('settings::admin.partials.menus')
        </div>
        <div class="col-lg-9 col-md-9 col-xm-12">
            <form id="admin-wallet-settings-edit-form" method="post">
                @method('patch')

                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Wallet settings')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Allow users to use the wallet')
                                </label>
                                <select class="form-control ml-auto" name="allow_wallet" id="allow_wallet">
                                    <option value="disable" @selected(setting('allow_wallet') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_wallet') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Allow users to withdraw money')
                                </label>
                                <select class="form-control ml-auto" name="allow_withdrawal" id="allow_withdrawal">
                                    <option value="disable" @selected(setting('allow_withdrawal') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_withdrawal') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Allow users to send money')
                                </label>
                                <select class="form-control ml-auto" name="allow_send_money" id="allow_send_money">
                                    <option value="disable" @selected(setting('allow_send_money') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_send_money') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Wallet has multiple currency')
                                </label>
                                <select class="form-control ml-auto" name="allow_wallet_multi_currency" id="allow_wallet_multi_currency">
                                    <option value="disable" @selected(setting('allow_wallet_multi_currency') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_wallet_multi_currency') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Allow wallet as payments for subscriptions')
                                </label>
                                <select class="form-control ml-auto" name="allow_wallet_in_subscription" id="allow_wallet_in_subscription">
                                    <option value="disable" @selected(setting('allow_wallet_in_subscription') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_wallet_in_subscription') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>
                        <p><b>@lang('Wallet exchange charges by currency:')</b></p>
                        <table class="table mb-0 wallet-charge-settings" data-system-currency="{{ setting('currency') }}">
                            <thead>
                                <tr>
                                <th scope="col">@lang('Wallet')</th>
                                <th scope="col">@lang('Fixed charge')</th>
                                <th scope="col">@lang('Percent charge rate')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($currencies as $currency)
                                <tr data-currency="{{$currency->code}}" class="{{ setting('allow_wallet_multi_currency') !== 'enable' ? (setting('currency') === $currency->code ? '' : 'd-none') : '' }}">
                                    <td>{{ $currency->code }}</td>
                                    <td>
                                        <div class="form-group mb-0">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="{{ $currency->code }}_wallet_exchange_charge_amount"
                                                name="{{ $currency->code }}_wallet_exchange_charge_amount"
                                                value="{{ setting($currency->code.'_wallet_exchange_charge_amount', 0) }}"
                                                data-decimals="2"
                                            >
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="{{ $currency->code }}_wallet_exchange_charge_rate"
                                                name="{{ $currency->code }}_wallet_exchange_charge_rate"
                                                value="{{ setting($currency->code.'_wallet_exchange_charge_rate', 0) }}"
                                                data-decimals="2"
                                            >
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Deposit email settings')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send email to every admin on deposit request created for approval')
                                </label>
                                <select class="form-control ml-auto" name="allow_deposit_approval_email" id="allow_deposit_approval_email">
                                    <option value="disable" @selected(setting('allow_deposit_approval_email') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_deposit_approval_email') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send email to user when deposit completed(Automatic gateways).')
                                </label>
                                <select class="form-control ml-auto" name="allow_deposit_completed_email" id="allow_deposit_completed_email">
                                    <option value="disable" @selected(setting('allow_deposit_completed_email') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_deposit_completed_email') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send email to user when deposit request approved(Manual methods).')
                                </label>
                                <select class="form-control ml-auto" name="allow_deposit_approved_email" id="allow_deposit_approved_email">
                                    <option value="disable" @selected(setting('allow_deposit_approved_email') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_deposit_approved_email') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-0">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send email to user when deposit request rejected(Manual methods).')
                                </label>
                                <select class="form-control ml-auto" name="allow_deposit_rejected_email" id="allow_deposit_rejected_email">
                                    <option value="disable" @selected(setting('allow_deposit_rejected_email') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_deposit_rejected_email') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Withdrawal email settings')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send email to every admin on withdrawal request created for approval')
                                </label>
                                <select class="form-control ml-auto" name="allow_withdraw_approval_email" id="allow_withdraw_approval_email">
                                    <option value="disable" @selected(setting('allow_withdraw_approval_email') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_withdraw_approval_email') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send email to user when withdrawal request approved.')
                                </label>
                                <select class="form-control ml-auto" name="allow_withdraw_approved_email" id="allow_withdraw_approved_email">
                                    <option value="disable" @selected(setting('allow_withdraw_approved_email') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_withdraw_approved_email') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-0">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send email to user when withdrawal request rejected.')
                                </label>
                                <select class="form-control ml-auto" name="allow_withdraw_rejected_email" id="allow_withdraw_rejected_email">
                                    <option value="disable" @selected(setting('allow_withdraw_rejected_email') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_withdraw_rejected_email') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Send money charge settings')</h4>
                    </div>
                    <div class="card-body">
                        <table class="table mb-0 wallet-charge-settings" data-system-currency="{{ setting('currency') }}">
                            <thead>
                                <tr>
                                <th scope="col">@lang('Wallet')</th>
                                <th scope="col">@lang('Fixed charge')</th>
                                <th scope="col">@lang('Percent Charge Rate')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($currencies as $currency)
                                <tr data-currency="{{$currency->code}}" class="{{ setting('allow_wallet_multi_currency') !== 'enable' ? (setting('currency') === $currency->code ? '' : 'd-none') : '' }}">
                                    <td>{{ $currency->code }}</td>
                                    <td>
                                        <div class="form-group mb-0">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="{{ $currency->code }}_wallet_send_money_charge_amount"
                                                name="{{ $currency->code }}_wallet_send_money_charge_amount"
                                                value="{{ setting($currency->code.'_wallet_send_money_charge_amount', 0) }}"
                                                data-decimals="2"
                                            >
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="{{ $currency->code }}_wallet_send_money_charge_rate"
                                                name="{{ $currency->code }}_wallet_send_money_charge_rate"
                                                value="{{ setting($currency->code.'_wallet_send_money_charge_rate', 0) }}"
                                                data-decimals="2"
                                            >
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>@lang('Send money email settings')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send email after every successful send money transaction to sender user.')
                                </label>
                                <select class="form-control ml-auto" name="allow_send_money_completed_sender_email" id="allow_send_money_completed_sender_email">
                                    <option value="disable" @selected(setting('allow_send_money_completed_sender_email') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_send_money_completed_sender_email') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-0">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Send email after every successful send money transaction to receiver user.')
                                </label>
                                <select class="form-control ml-auto" name="allow_send_money_completed_receiver_email" id="allow_send_money_completed_receiver_email">
                                    <option value="disable" @selected(setting('allow_send_money_completed_receiver_email') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_send_money_completed_receiver_email') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>@lang('One-Time Password Settings')</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Confirm wallet exhange via OTP.')
                                </label>
                                <select class="form-control ml-auto" name="allow_otp_wallet_exchange" id="allow_otp_wallet_exchange">
                                    <option value="disable" @selected(setting('allow_otp_wallet_exchange') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_otp_wallet_exchange') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-4">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Confirm send money via OTP.')
                                </label>
                                <select class="form-control ml-auto" name="allow_otp_send_money" id="allow_otp_send_money">
                                    <option value="disable" @selected(setting('allow_otp_send_money') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_otp_send_money') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-inline w-100 mb-0">
                            <div class="form-group w-100">
                                <label class="mr-4">
                                    @lang('Confirm withdrawals via OTP.')
                                </label>
                                <select class="form-control ml-auto" name="allow_otp_wothdrawal" id="allow_otp_wothdrawal">
                                    <option value="disable" @selected(setting('allow_otp_wothdrawal') === 'disable')>@lang('Disable')</option>
                                    <option value="enable" @selected(setting('allow_otp_wothdrawal') === 'enable')>@lang('Enable')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                </div>
            </form>
        </div>
    </div>
@endsection
