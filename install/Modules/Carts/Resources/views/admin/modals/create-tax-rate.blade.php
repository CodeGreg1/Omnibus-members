<div class="modal fade" tabindex="-1" role="dialog" id="create-tax-rate-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('New tax rate')</h5>
                <a href="#" role="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                <form id="createTaxRateForm">
                    <div class="form-group">
                        <label for="title">@lang('Title')</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="@lang('Title')">
                    </div>
                    <div class="form-group">
                        <label for="description">@lang('Description')</label>
                        <input type="text" class="form-control" id="description" name="description" description="price" placeholder="@lang('Description')">
                    </div>
                    <div class="form-group">
                        <label>@lang('Rate')</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="percentage" id="percentage">
                            <select class="custom-select" id="inclusive" name="inclusive">
                                <option selected="" value="1">@lang('Inclusive')</option>
                                <option value="0">@lang('Exclusive')</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tax_type">@lang('Tax type')</label>
                        <select class="form-control" id="tax_type" name="tax_type">
                            <option value="vat">VAT</option>
                            <option value="sales_tax">@lang('Sales tax')</option>
                        </select>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" name="active" id="active">
                        <label class="custom-control-label" for="active">@lang('Active')</label>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button
                            type="button"
                            class="btn btn-primary btn-create-tax-rate"
                            data-href="{{ route('admin.tax-rates.store') }}"
                        >@lang('Save')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
