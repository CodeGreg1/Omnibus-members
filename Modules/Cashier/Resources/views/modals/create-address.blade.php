<div class="modal fade" tabindex="-1" role="dialog" id="create-address-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('New address')</h5>
                <button type="button" class="close fs-5" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cart-address-create-form">
                    <div class="form-group mb-3">
                        <label class="sr-only" for="name">@lang('Name')</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="@lang('Name')">
                    </div>
                    <div class="form-group mb-3">
                        <label class="sr-only" for="description">@lang('Description')</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="@lang('Description')">
                    </div>
                    <div class="form-group mb-3">
                        <label class="sr-only" for="address_1">@lang('Address')</label>
                        <input type="text" class="form-control" id="address_1" name="address_1" placeholder="@lang('Address')">
                    </div>
                    <div class="form-group mb-3">
                        <label class="sr-only" for="address_2">@lang('Address') 2</label>
                        <input type="text" class="form-control" id="address_2" name="address_2" placeholder="@lang('Apartment, suite, etc.(optional)')">
                    </div>
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="state" id="state" placeholder="State">
                            <input type="text" class="form-control" name="city" id="city" placeholder="@lang('City')">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="sr-only" for="country_id">@lang('Country')</label>
                        <select class="custom-select" id="country_id" name="country_id">
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3 w-50">
                        <label class="sr-only" for="zip_code">@lang('ZIP code')</label>
                        <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="@lang('ZIP code')">
                    </div>
                </form>

            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button
                    type="button"
                    class="btn btn-primary"
                    id="btn-create-cart-address"
                    data-href="{{ route('user.carts.index') }}"
                >@lang('Save')</button>
            </div>
        </div>
    </div>
</div>
