<div class="modal fade wallet-form" tabindex="-1" role="dialog" id="wallet-exchange-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Wallet Exchange')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="wallet-exchange-form">
                    <input type="hidden" name="from_currency" value="{{ $defaultWallet->code }}">

                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>@lang('Balance')</strong>
                                <h3 class="wallet-balance mb-0 {{ $defaultWallet->balance ? 'text-success' : 'text-danger' }}">
                                    {{ currency_format($defaultWallet->balance, $defaultWallet->code) }}
                                </h3>
                            </div>
                        </li>
                        <li class="list-group-item mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>@lang('Charge')</strong>

                                <strong class="d-flex align-items-center text-danger">
                                    <span class="wallet-exchange-fixed-amount">{{ currency_format(wallet_charge($defaultWallet->code), $defaultWallet->code) }}</span>
                                    <span class="mx-1">+</span>
                                    <span class="wallet-exchange-rate">{{ wallet_rate($defaultWallet->code) }}</span>%
                                </strong>
                            </div>
                        </li>
                    </ul>

                    <div class="form-group">
                        <label class="d-block" for="amount">@lang('Amount') <span class="text-muted">(@lang('Required'))</span></label>
                        <input type="text" name="amount" data-decimals="2" class="form-control form-control-lg" placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="to_currency">@lang('To Wallet') <span class="text-muted">(@lang('Required'))</span></label>
                        <select name="to_currency" class="form-control" required>
                            <option value="">@lang('Select currency')</option>
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->code }}">{{ $wallet->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">@lang('Continue')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
