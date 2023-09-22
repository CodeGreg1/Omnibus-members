<div class="modal fade" tabindex="-1" role="dialog" id="edit-package-price-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Edit price')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-package-price-form" data-route="">
                    <div class="form-row">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="package_id" name="package_id">
                        <div class="form-group col-md-6">
                            <label for="price">@lang('Price')</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select class="custom-select" id="currency" name="currency" style="width:100px;">
                                        @foreach ($currencies as $currencyItem)
                                            <option
                                                value="{{ $currencyItem->code }}"
                                                @selected($currencyItem->code === $currency)
                                            >{{ $currencyItem->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="text" class="form-control" id="price" name="price" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="compare_at_price">@lang('Compare at price')</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="compare_at_price" name="compare_at_price" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-pills" id="planTypeTab" role="tablist">
                        <li class="nav-item">
                            <a class="planType nav-link active show" data-toggle="tab" href="#recurring-payment" role="tab" aria-controls="recurring-payment" data-id="recurring" aria-selected="true">@lang('Recurring')</a>
                        </li>
                        <li class="nav-item">
                            <a class="planType nav-link" data-toggle="tab" href="#onetime-payment" role="tab" aria-controls="onetime-payment" aria-selected="false" data-id="onetime">@lang('One time')</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="planTypeTab">
                        <div class="tab-pane fade active show pb-0" id="recurring-payment" role="tabpanel" aria-labelledby="recurring-payment">
                            <div class="form-group">
                                <label for="package_term_id">@lang('Term')</label>
                                <select class="form-control select2" id="package_term_id" name="package_term_id">
                                    @foreach ($terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="trial_days_count">@lang('Free trial')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="trial_days_count" name="trial_days_count" placeholder="0">
                                    <div class="input-group-append">
                                        <div class="input-group-text">@lang('days')</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="onetime-payment" role="tabpanel" aria-labelledby="onetime-payment">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-primary" id="btn-update-package-price">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
