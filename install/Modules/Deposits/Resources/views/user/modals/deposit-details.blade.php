<div class="modal fade" tabindex="-1" role="dialog" id="deposit-details-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Details')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6 pr-2 text-right">
                                <strong>@lang('Amount:')</strong>
                            </div>
                            <div class="col-6 pl-2">
                                <strong class="checkout-details-amount">0</strong>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6 pr-2 text-right">
                                <strong>@lang('Charge:')</strong>
                            </div>
                            <div class="col-6 pl-2">
                                <strong class="checkout-details-charge">0</strong>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6 pr-2 text-right">
                                <strong>@lang('Payable:')</strong>
                            </div>
                            <div class="col-6 pl-2">
                                <strong class="checkout-details-payable">0</strong>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
