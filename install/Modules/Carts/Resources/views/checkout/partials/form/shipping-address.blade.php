<form id="checkout-newShippingAddressForm" class="form-group-row {{ count($addresses) ? 'list-select-accordion-body my-2' : '' }}">
    <div class="form-group">
        <label class="sr-only" for="name">@lang('Name')</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="@lang('Name')">
    </div>
    <div class="form-group">
        <label class="sr-only" for="description">@lang('Description')</label>
        <input type="text" class="form-control" id="description" name="description" placeholder="@lang('Description')">
    </div>
    <div class="form-group">
        <label class="sr-only" for="address_1">@lang('Address')</label>
        <input type="text" class="form-control" id="address_1" name="address_1" placeholder="@lang('Address')">
    </div>
    <div class="form-group">
        <label class="sr-only" for="address_2">@lang('Address') 2</label>
        <input type="text" class="form-control" id="address_2" name="address_2" placeholder="Apartment, suite, etc.(optional)">
    </div>
    <div class="form-group">
        <label class="sr-only" for="state">@lang('State')</label>
        <input type="text" class="form-control" name="state" id="state" placeholder="@lang('State')">
    </div>
    <div class="form-group">
        <label class="sr-only" for="city">@lang('City')</label>
        <input type="text" class="form-control" name="city" id="city" placeholder="@lang('City')">
    </div>
    <div class="form-group">
        <label class="sr-only" for="country_id">@lang('Country')</label>
        <select class="form-control custom-select" id="country_id" name="country_id">
            @foreach ($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="sr-only" for="zip_code">@lang('ZIP code')</label>
        <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="@lang('ZIP code')">
    </div>
    </form>
