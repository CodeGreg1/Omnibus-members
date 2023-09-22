<div class="modal fade" tabindex="-1" role="dialog" id="edit-shipping-rate-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Edit shipping rate')</h5>
                <a href="#" role="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                <form id="updateShippingRateForm">
                    <input type="hidden" class="form-control" id="id" name="id" placeholder="">
                    <div class="form-group">
                        <label for="title">@lang('Title')</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="@lang('Title')">
                    </div>
                    <div class="form-group">
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
                            <input type="number" class="form-control" id="price" name="price" placeholder="0">
                        </div>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" name="edit-active" id="edit-active">
                        <label class="custom-control-label" for="edit-active">@lang('Active')</label>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button
                            type="button"
                            class="btn btn-primary btn-update-shipping-rate"
                        >@lang('Save changes')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
