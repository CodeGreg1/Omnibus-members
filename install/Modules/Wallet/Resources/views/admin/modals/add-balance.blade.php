<div class="modal fade" tabindex="-1" role="dialog" id="user-add-balance-admin-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add Money')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="user-add-balance-admin-form">
                    <input type="hidden" name="user" value="{{ $user->id }}">

                    <div class="form-group">
                        <label class="d-block" for="user">@lang('User') <span class="text-muted">(@lang('Required'))</span></label>
                        <input type="text" class="form-control" readonly value="{{ $user->email }}">
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="d-block" for="currency">@lang('Currency') <span class="text-muted">(@lang('Required'))</span></label>
                                <select name="currency" class="form-control select2">
                                    @if (setting('allow_wallet_multi_currency') === 'enable')
                                        @foreach (currency()->getActiveCurrencies() as $code => $currency)
                                            <option value="{{ $code }}">{{ $currency['name'] }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ setting('currency') }}">{{ currency()->getCurrencyProp(setting('currency'), 'name') }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                             <div class="form-group">
                                <label class="d-block" for="amount">@lang('Amount') <span class="text-muted">(@lang('Required'))</span></label>
                                <input type="text" name="amount" data-decimals="2" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">@lang('Continue')</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
