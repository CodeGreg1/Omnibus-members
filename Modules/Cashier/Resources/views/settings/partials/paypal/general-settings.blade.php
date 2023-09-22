<div class="tab-pane fade show active" id="paypal-gateway-general-esettings" role="tabpanel" aria-labelledby="paypal-gateway-general-esettings-tab">
    <form id="paypal-gateway-general-esettings-settings-form" data-route="{{ route('admin.settings.payment-gateways.general-settings.update') }}">
        <h6>@lang('General Settings')</h6>
        <div class="mt-3">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="form-group">
                        <label>@lang('Name')  <span class="text-muted">(@lang('Required'))</span></label>
                            <input type="text" name="paypal_display_name" class="form-control" value="{{ setting('paypal_display_name') }}">
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <div class="form-group mb-0">
                        <label for="paypal_logo">@lang('Logo') <span class="text-muted">(@lang('Required'))</span></label>
                        <div data-image-gallery="paypal" name="paypal_logo">
                            @if (setting('paypal_logo'))
                                <ul class="list-group">
                                    <li class="list-group-item" data-id="0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="gallery-photo-preview">
                                                    <img class="rounded" src="{{ setting('paypal_logo') }}" alt="Logo" data-dz-thumbnail="" data-src="{{ setting('paypal_logo') }}">
                                                </div>
                                            </div>
                                            <div class="col overflow-hidden">
                                                <h6 class="text-sm mb-1 image-name" data-name="Logo">Logo</h6>
                                            </div>
                                            <div class="col-auto">
                                                <a href="javascript:void(0)" class="dropdown-item btn-remove-selected-gallery-image"><i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            @endif
                        </div>
                        <small class="form-text text-muted form-help">
                            @lang('Recommended and max size is 500x500.')
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
